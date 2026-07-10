@extends('layouts.app')

@section('title', 'Manajemen Pesanan F&B')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-burger text-[#6C3BFF]"></i> Pesanan Makanan & Minuman
        </h2>
        <p class="text-sm text-slate-400 mt-1">Pantau pesanan dari meja pelanggan, teruskan ke dapur, dan perbarui status saji.</p>
    </div>

    <!-- Orders Table -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800/80">
                        <th class="pb-3 font-semibold">ID Pesanan</th>
                        <th class="pb-3 font-semibold">Meja & Pelanggan</th>
                        <th class="pb-3 font-semibold">Menu & Kuantitas</th>
                        <th class="pb-3 font-semibold">Subtotal</th>
                        <th class="pb-3 font-semibold">Waktu Pesan</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold text-right">Ubah Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 font-mono font-bold text-slate-300">#{{ $order->id }}</td>
                            <td class="py-4">
                                <span class="block font-semibold text-slate-200">Meja {{ $order->booking->table->table_number ?? 'N/A' }}</span>
                                <span class="text-xs text-slate-500">{{ $order->user->name }}</span>
                            </td>
                            <td class="py-4">
                                <ul class="list-disc pl-4 text-xs text-slate-300 space-y-1">
                                    @foreach($order->details as $det)
                                        <li>{{ $det->item_name }} <strong class="text-slate-200">({{ $det->quantity }}x)</strong></li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-4 font-bold text-slate-300">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="py-4 text-slate-400">{{ $order->created_at->format('H:i') }} WIB</td>
                            <td class="py-4">
                                @if($order->status === 'Pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-950 text-yellow-450 border border-yellow-900/20">Pending</span>
                                @elseif($order->status === 'Processing')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-950 text-blue-400 border border-blue-900/20">Dapur</span>
                                @elseif($order->status === 'Ready')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-950 text-purple-400 border border-purple-900/20">Siap Saji</span>
                                @elseif($order->status === 'Delivered')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-950 text-green-400 border border-green-900/20">Selesai</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-950 text-red-400 border border-red-900/20">Batal</span>
                                @endif
                            </td>
                            <td class="py-4 text-right">
                                <form method="POST" action="{{ route('kasir.orders.update-status', $order->id) }}" class="inline-block">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="bg-slate-900 border border-slate-800 rounded-xl px-2.5 py-1.5 text-xs text-slate-200 focus:ring-0">
                                        <option value="Pending" {{ $order->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Processing" {{ $order->status === 'Processing' ? 'selected' : '' }}>Proses Dapur</option>
                                        <option value="Ready" {{ $order->status === 'Ready' ? 'selected' : '' }}>Siap Antar</option>
                                        <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                <i class="fa-solid fa-burger text-3xl mb-2 block"></i>
                                Belum ada pesanan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
