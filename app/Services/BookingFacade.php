<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BilliardTable;
use App\Models\Notification;
use App\Models\Transaction;
use App\Services\Pricing\PricingContext;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Facade Pattern — Antarmuka tunggal untuk proses checkout reservasi
 * 
 * Menyederhanakan proses kompleks yang melibatkan:
 * - Validasi ketersediaan meja
 * - Kalkulasi harga (Strategy Pattern)
 * - Pembuatan booking & transaksi
 * - Pengiriman notifikasi
 * 
 * Frontend cukup memanggil BookingFacade::confirmBooking()
 * tanpa perlu tahu detail implementasi di balik layar.
 */
class BookingFacade
{
    private PricingContext $pricingContext;

    public function __construct()
    {
        $this->pricingContext = new PricingContext();
    }

    /**
     * Satu metode untuk menyelesaikan seluruh proses booking.
     *
     * @param array $bookingData Data booking dari form
     * @return array ['success' => bool, 'booking' => Booking|null, 'message' => string]
     */
    public function confirmBooking(array $bookingData): array
    {
        // Step 1: Cek ketersediaan meja (menghindari double booking)
        $availability = $this->checkTableAvailability(
            $bookingData['table_id'],
            $bookingData['start_time'],
            $bookingData['end_time']
        );

        if (!$availability['available']) {
            return [
                'success' => false,
                'booking' => null,
                'message' => "Meja tidak tersedia: {$availability['reason']}",
            ];
        }

        // Step 2: Hitung harga menggunakan Strategy Pattern
        $table = BilliardTable::findOrFail($bookingData['table_id']);
        $user  = auth()->user();

        $startTime     = Carbon::parse($bookingData['start_time']);
        $endTime       = Carbon::parse($bookingData['end_time']);
        $durationHours = $startTime->diffInMinutes($endTime) / 60;

        $this->pricingContext->selectStrategy($startTime, $user);
        $priceResult = $this->pricingContext->calculate($table, $durationHours, [
            'member_type' => $user?->member_type ?? 'Bronze',
        ]);

        // Step 3: Buat booking
        $booking = Booking::create([
            'id'          => 'BK' . strtoupper(Str::random(9)),
            'user_id'     => $user->id,
            'table_id'    => $bookingData['table_id'],
            'start_time'  => $startTime,
            'end_time'    => $endTime,
            'total_price' => $priceResult['total'],
            'price_type'  => $priceResult['type'],
            'status'      => 'Pending',
            'notes'       => $bookingData['notes'] ?? null,
        ]);

        // Step 4: Update status meja ke Booked
        $table->transitionToBooked();

        // Step 5: Kirim notifikasi konfirmasi (simulasi)
        $this->sendBookingConfirmationNotification($booking);

        return [
            'success' => true,
            'booking' => $booking,
            'message' => "Booking berhasil dibuat! Total: Rp " . number_format($priceResult['total'], 0, ',', '.'),
        ];
    }

    /**
     * Cek ketersediaan meja — mencegah double booking
     */
    public function checkTableAvailability(string $tableId, string $startTime, string $endTime): array
    {
        $table = BilliardTable::find($tableId);

        if (!$table) {
            return ['available' => false, 'reason' => 'Meja tidak ditemukan'];
        }

        if ($table->isMaintenance()) {
            return ['available' => false, 'reason' => 'Meja sedang dalam maintenance'];
        }

        // Cek overlap jadwal (mencegah double booking)
        $conflict = Booking::where('table_id', $tableId)
            ->whereIn('status', ['Pending', 'Confirmed', 'Active'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })
            ->first();

        if ($conflict) {
            return [
                'available' => false,
                'reason'    => "Meja sudah dipesan pada " . Carbon::parse($conflict->start_time)->format('H:i') . " - " . Carbon::parse($conflict->end_time)->format('H:i'),
            ];
        }

        return ['available' => true, 'reason' => null];
    }

    /**
     * Perpanjang durasi booking (Extend Time)
     */
    public function extendBooking(Booking $booking, int $extraMinutes = 60): array
    {
        if (!$booking->canExtend()) {
            return ['success' => false, 'message' => 'Waktu tidak dapat diperpanjang. Ada booking berikutnya.'];
        }

        $table        = $booking->table;
        $newEndTime   = Carbon::parse($booking->end_time)->addMinutes($extraMinutes);
        $extraHours   = $extraMinutes / 60;

        $this->pricingContext->selectStrategy(Carbon::parse($booking->end_time), $booking->user);
        $extraPrice = $this->pricingContext->calculate($table, $extraHours, [
            'member_type' => $booking->user?->member_type ?? 'Bronze',
        ]);

        $booking->update([
            'end_time'       => $newEndTime,
            'total_price'    => $booking->total_price + $extraPrice['total'],
            'extended_count' => $booking->extended_count + 1,
        ]);

        // Notifikasi extend time
        Notification::create([
            'id'         => 'NT' . strtoupper(Str::random(9)),
            'user_id'    => $booking->user_id,
            'booking_id' => $booking->id,
            'type'       => 'extend_time',
            'title'      => 'Waktu Diperpanjang',
            'message'    => "Waktu bermain Anda berhasil diperpanjang {$extraMinutes} menit hingga " . $newEndTime->format('H:i') . ". Biaya tambahan: Rp " . number_format($extraPrice['total'], 0, ',', '.'),
            'sent_at'    => now(),
        ]);

        return [
            'success'     => true,
            'message'     => "Waktu berhasil diperpanjang hingga " . $newEndTime->format('H:i'),
            'extra_price' => $extraPrice['total'],
        ];
    }

    /**
     * Kirim notifikasi konfirmasi booking (simulasi — disimpan ke database)
     */
    private function sendBookingConfirmationNotification(Booking $booking): void
    {
        Notification::create([
            'id'         => 'NT' . strtoupper(Str::random(9)),
            'user_id'    => $booking->user_id,
            'booking_id' => $booking->id,
            'type'       => 'booking_confirmed',
            'title'      => 'Booking Dikonfirmasi',
            'message'    => "Booking meja #{$booking->table->table_number} berhasil! Waktu: {$booking->start_time->format('d/m/Y H:i')} - {$booking->end_time->format('H:i')}. Total: Rp " . number_format($booking->total_price, 0, ',', '.'),
            'sent_at'    => now(),
        ]);
    }

    /**
     * Kirim pengingat 15 menit sebelum waktu habis (dipanggil via scheduler)
     */
    public function sendExpiryReminder(Booking $booking): void
    {
        Notification::create([
            'id'         => 'NT' . strtoupper(Str::random(9)),
            'user_id'    => $booking->user_id,
            'booking_id' => $booking->id,
            'type'       => 'booking_reminder',
            'title'      => '⏰ Waktu Bermain Hampir Habis!',
            'message'    => "Sisa waktu bermain Anda di meja #{$booking->table->table_number} tinggal 15 menit. Selesai pada: {$booking->end_time->format('H:i')}. Perpanjang sekarang jika ingin lanjut bermain.",
            'sent_at'    => now(),
        ]);
    }
}
