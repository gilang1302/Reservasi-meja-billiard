<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\MenuItem;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * OrderController — Pemesanan F&B dari meja
 * 
 * Decorator Pattern digunakan untuk menghitung total harga:
 * Booking base price + F&B price + Equipment rental price
 */
class OrderController extends Controller
{
    /**
     * Tampilkan menu F&B
     */
    public function menu(string $bookingId)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->whereIn('status', ['Confirmed', 'Active'])
            ->findOrFail($bookingId);

        $menuItems = MenuItem::where('is_available', true)->orderBy('category')->get();
        $groupedMenu = $menuItems->groupBy('category');

        $existingOrder = $booking->order()->with('details')->first();

        return view('customer.orders.menu', compact('booking', 'groupedMenu', 'existingOrder'));
    }

    /**
     * Buat order F&B baru
     */
    public function store(Request $request, string $bookingId)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->whereIn('status', ['Confirmed', 'Active'])
            ->findOrFail($bookingId);

        $validated = $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:menu_item,id',
            'items.*.qty'    => 'required|integer|min:1|max:10',
        ]);

        $orderId = 'OR' . strtoupper(Str::random(9));
        $totalPrice = 0;
        $details = [];

        foreach ($validated['items'] as $item) {
            $menuItem  = MenuItem::findOrFail($item['id']);
            $subtotal  = $menuItem->price * $item['qty'];
            $totalPrice += $subtotal;

            $details[] = [
                'id'           => 'OD' . strtoupper(Str::random(9)),
                'order_id'     => $orderId,
                'menu_item_id' => $menuItem->id,
                'item_name'    => $menuItem->name,
                'quantity'     => $item['qty'],
                'price'        => $menuItem->price,
                'subtotal'     => $subtotal,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        // Buat order
        Order::create([
            'id'         => $orderId,
            'booking_id' => $bookingId,
            'user_id'    => auth()->id(),
            'total_price' => $totalPrice,
            'status'     => 'Pending',
        ]);

        OrderDetail::insert($details);

        // Notifikasi ke kasir (simulasi)
        Notification::create([
            'id'         => 'NT' . strtoupper(Str::random(9)),
            'user_id'    => auth()->id(),
            'booking_id' => $bookingId,
            'type'       => 'fnb_ready',
            'title'      => '🍔 Pesanan F&B Diterima',
            'message'    => "Pesanan F&B Anda di meja #{$booking->table->table_number} sedang diproses. Total: Rp " . number_format($totalPrice, 0, ',', '.'),
            'sent_at'    => now(),
        ]);

        return redirect()->route('booking.show', $bookingId)
            ->with('success', 'Pesanan berhasil dikirim ke kasir!');
    }

    /**
     * Kasir: Lihat semua order masuk
     */
    public function kasirIndex()
    {
        $orders = Order::with(['booking.table', 'user', 'details.menuItem'])
            ->orderByRaw("CASE status WHEN 'Pending' THEN 0 WHEN 'Processing' THEN 1 WHEN 'Ready' THEN 2 ELSE 3 END")
            ->latest()->paginate(20);

        return view('kasir.orders.index', compact('orders'));
    }

    /**
     * Kasir: Update status order
     */
    public function updateStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:Pending,Processing,Ready,Delivered,Cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        // Notifikasi ke pelanggan jika pesanan siap
        if ($validated['status'] === 'Ready') {
            Notification::create([
                'id'         => 'NT' . strtoupper(Str::random(9)),
                'user_id'    => $order->user_id,
                'booking_id' => $order->booking_id,
                'type'       => 'fnb_ready',
                'title'      => '🍔 Pesanan Siap Diantar!',
                'message'    => "Pesanan F&B Anda sudah siap dan sedang diantarkan ke meja Anda.",
                'sent_at'    => now(),
            ]);
        }

        return back()->with('success', "Status pesanan diperbarui ke {$validated['status']}.");
    }
}
