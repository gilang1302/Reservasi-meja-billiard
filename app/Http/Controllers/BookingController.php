<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BilliardTable;
use App\Models\Transaction;
use App\Services\BookingFacade;
use App\Services\Pricing\PricingContext;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    private BookingFacade $bookingFacade;

    public function __construct()
    {
        $this->bookingFacade = new BookingFacade();
    }

    /**
     * Tampilkan denah meja interaktif untuk memilih meja
     */
    public function index(Request $request)
    {
        $date      = $request->get('date', now()->format('Y-m-d'));
        $startTime = $request->get('start_time', '');
        $endTime   = $request->get('end_time', '');

        $tables = BilliardTable::all();

        // Jika ada waktu yang dipilih, tandai meja yang sudah dipesan
        $bookedTableIds = [];
        if ($startTime && $endTime) {
            $availability = $this->bookingFacade->checkTableAvailability('', "$date $startTime", "$date $endTime");
            $bookedTableIds = Booking::where(function ($query) use ($date, $startTime, $endTime) {
                $start = "$date $startTime";
                $end   = "$date $endTime";
                $query->whereIn('status', ['Pending', 'Confirmed', 'Active'])
                    ->where(function ($q) use ($start, $end) {
                        $q->whereBetween('start_time', [$start, $end])
                          ->orWhereBetween('end_time', [$start, $end])
                          ->orWhere(function ($q2) use ($start, $end) {
                              $q2->where('start_time', '<=', $start)->where('end_time', '>=', $end);
                          });
                    });
            })->pluck('table_id')->toArray();
        }

        $isPeakHour = $startTime ? PricingContext::isPeakHour(Carbon::parse("$date $startTime")) : false;

        return view('customer.booking.index', compact('tables', 'date', 'startTime', 'endTime', 'bookedTableIds', 'isPeakHour'));
    }

    /**
     * Tampilkan form konfirmasi booking
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'table_id'   => 'required|exists:table,id',
            'date'       => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $table     = BilliardTable::findOrFail($validated['table_id']);
        $startTime = Carbon::parse("{$validated['date']} {$validated['start_time']}");
        $endTime   = Carbon::parse("{$validated['date']} {$validated['end_time']}");

        if ($endTime->lte($startTime)) {
            return back()->withErrors(['end_time' => 'Waktu selesai harus setelah waktu mulai.']);
        }

        $durationHours = $startTime->diffInMinutes($endTime) / 60;

        // Hitung harga menggunakan Strategy Pattern
        $pricingContext = new PricingContext();
        $pricingContext->selectStrategy($startTime, auth()->user());
        $priceResult = $pricingContext->calculate($table, $durationHours, [
            'member_type' => auth()->user()->member_type ?? 'Bronze',
        ]);

        // Cek ketersediaan
        $availability = $this->bookingFacade->checkTableAvailability(
            $validated['table_id'],
            $startTime->toDateTimeString(),
            $endTime->toDateTimeString()
        );

        return view('customer.booking.create', compact(
            'table', 'startTime', 'endTime', 'durationHours', 'priceResult', 'availability', 'validated'
        ));
    }

    /**
     * Proses booking — Facade Pattern
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id'   => 'required|exists:table,id',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
            'notes'      => 'nullable|string|max:500',
        ]);

        $result = $this->bookingFacade->confirmBooking($validated);

        if (!$result['success']) {
            return back()->withErrors(['error' => $result['message']]);
        }

        return redirect()->route('booking.show', $result['booking']->id)
            ->with('success', $result['message']);
    }

    /**
     * Tampilkan detail booking + pembayaran
     */
    public function show(string $id)
    {
        $booking = Booking::with(['table', 'user', 'transaction', 'order.details', 'feedback'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('customer.booking.show', compact('booking'));
    }

    /**
     * Perpanjang durasi booking (Extend Time)
     */
    public function extend(Request $request, string $id)
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($id);

        $extraMinutes = (int) $request->get('extra_minutes', 60);

        $result = $this->bookingFacade->extendBooking($booking, $extraMinutes);

        if (!$result['success']) {
            return back()->withErrors(['error' => $result['message']]);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Batalkan booking
     */
    public function cancel(string $id)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->where('status', 'Pending')
            ->findOrFail($id);

        $booking->update(['status' => 'Cancelled']);
        $booking->table->transitionToAvailable();

        return redirect()->route('dashboard')->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Riwayat booking user
     */
    public function history()
    {
        $bookings = auth()->user()->bookings()
            ->with('table', 'transaction', 'feedback')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.booking.history', compact('bookings'));
    }

    /**
     * API: Cek ketersediaan slot waktu (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        $availability = $this->bookingFacade->checkTableAvailability(
            $request->table_id,
            $request->start_time,
            $request->end_time
        );

        return response()->json($availability);
    }

    /**
     * API: Hitung harga (AJAX)
     */
    public function calculatePrice(Request $request)
    {
        $table     = BilliardTable::findOrFail($request->table_id);
        $startTime = Carbon::parse($request->start_time);
        $endTime   = Carbon::parse($request->end_time);
        $duration  = $startTime->diffInMinutes($endTime) / 60;

        $pricingContext = new PricingContext();
        $pricingContext->selectStrategy($startTime, auth()->user());
        $result = $pricingContext->calculate($table, $duration, [
            'member_type' => auth()->user()->member_type ?? 'Bronze',
        ]);

        return response()->json($result);
    }

    /**
     * Kasir: Tampilkan daftar semua booking
     */
    public function kasirIndex()
    {
        $bookings = Booking::with('table', 'user', 'transaction')
            ->orderByRaw("CASE status WHEN 'Pending' THEN 0 WHEN 'Confirmed' THEN 1 WHEN 'Active' THEN 2 ELSE 3 END")
            ->latest()
            ->paginate(15);

        return view('kasir.bookings.index', compact('bookings'));
    }

    /**
     * Kasir: Konfirmasi booking manual
     */
    public function confirmBooking(string $id)
    {
        $booking = Booking::with('table', 'user')->findOrFail($id);
        $booking->update(['status' => 'Confirmed']);
        $booking->table->transitionToBooked();

        \App\Models\Notification::create([
            'id'         => 'NT' . strtoupper(Str::random(9)),
            'user_id'    => $booking->user_id,
            'booking_id' => $booking->id,
            'type'       => 'payment_success',
            'title'      => '✅ Reservasi Dikonfirmasi!',
            'message'    => "Reservasi Meja #{$booking->table->table_number} Anda telah dikonfirmasi oleh kasir. Silakan datang tepat waktu.",
            'sent_at'    => now(),
        ]);

        return back()->with('success', "Booking #{$booking->id} berhasil dikonfirmasi.");
    }
}
