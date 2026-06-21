<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Tampilkan form pembayaran
     */
    public function create(string $bookingId)
    {
        $booking = Booking::with('table', 'user')
            ->where('user_id', auth()->id())
            ->findOrFail($bookingId);

        if ($booking->isPaid()) {
            return redirect()->route('booking.show', $bookingId)->with('info', 'Booking sudah dibayar.');
        }

        return view('customer.payment.create', compact('booking'));
    }

    /**
     * Proses pembayaran — simulasi transfer bank
     */
    public function store(Request $request, string $bookingId)
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($bookingId);

        $validated = $request->validate([
            'payment_method' => 'required|in:BCA,QRIS,Tunai',
            'payment_proof'  => 'nullable|image|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        // Buat transaksi dengan status Pending (menunggu verifikasi kasir)
        Transaction::create([
            'id'             => 'TX' . strtoupper(Str::random(9)),
            'booking_id'     => $bookingId,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'Pending',
            'total_amount'   => $booking->total_price,
            'payment_proof'  => $proofPath,
        ]);

        $booking->update(['status' => 'Confirmed']);

        // Notifikasi pembayaran menunggu verifikasi
        Notification::create([
            'id'         => 'NT' . strtoupper(Str::random(9)),
            'user_id'    => auth()->id(),
            'booking_id' => $bookingId,
            'type'       => 'payment_success',
            'title'      => 'Pembayaran Menunggu Verifikasi',
            'message'    => "Pembayaran Anda sedang diverifikasi oleh kasir. Booking meja #{$booking->table->table_number} akan segera dikonfirmasi.",
            'sent_at'    => now(),
        ]);

        return redirect()->route('booking.show', $bookingId)
            ->with('success', 'Pembayaran berhasil dikirim! Menunggu verifikasi kasir.');
    }

    /**
     * Kasir: verifikasi pembayaran
     */
    public function verify(Request $request, string $id)
    {
        $transaction = Transaction::with('booking.user', 'booking.table')->findOrFail($id);

        $status = $request->get('status', 'Success');
        $transaction->update([
            'payment_status' => $status,
            'verified_at'    => now(),
            'verified_by'    => auth()->id(),
        ]);

        if ($status === 'Success') {
            $transaction->booking->update(['status' => 'Confirmed']);

            // Notifikasi ke pelanggan
            Notification::create([
                'id'         => 'NT' . strtoupper(Str::random(9)),
                'user_id'    => $transaction->booking->user_id,
                'booking_id' => $transaction->booking_id,
                'type'       => 'payment_success',
                'title'      => '✅ Pembayaran Dikonfirmasi!',
                'message'    => "Pembayaran booking meja #{$transaction->booking->table->table_number} telah dikonfirmasi. Silakan datang sesuai jadwal.",
                'sent_at'    => now(),
            ]);
        }

        return back()->with('success', "Transaksi berhasil di-$status.");
    }

    /**
     * Kasir: Daftar semua transaksi
     */
    public function kasirIndex()
    {
        $transactions = Transaction::with('booking.user', 'booking.table')
            ->latest()->paginate(15);

        return view('kasir.transactions.index', compact('transactions'));
    }

    /**
     * Owner: Laporan transaksi
     */
    public function ownerIndex(Request $request)
    {
        $query = Transaction::with('booking.user', 'booking.table')
            ->where('payment_status', 'Success');

        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->latest()->paginate(20);

        return view('owner.transactions.index', compact('transactions'));
    }
}
