@extends('layouts.app')

@section('title', 'Transaksi Pembayaran')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-credit-card text-[#6C3BFF]"></i> Verifikasi & Riwayat Transaksi
        </h2>
        <p class="text-sm text-slate-400 mt-1">Daftar transaksi pembayaran masuk. Lakukan verifikasi bukti transfer pelanggan di bawah.</p>
    </div>

    <!-- Transactions List -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800/80">
                        <th class="pb-3 font-semibold">ID Transaksi</th>
                        <th class="pb-3 font-semibold">Pelanggan & Booking ID</th>
                        <th class="pb-3 font-semibold">Metode</th>
                        <th class="pb-3 font-semibold">Jumlah</th>
                        <th class="pb-3 font-semibold">Tanggal</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold text-right">Bukti & Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 font-mono font-bold text-slate-300">#{{ $tx->id }}</td>
                            <td class="py-4">
                                <span class="block font-semibold text-slate-200">{{ $tx->booking->user->name ?? 'N/A' }}</span>
                                <span class="text-xs text-slate-500">Booking: #{{ $tx->booking_id }}</span>
                            </td>
                            <td class="py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-slate-800 text-slate-400 border border-slate-700/50">
                                    {{ $tx->payment_method }}
                                </span>
                            </td>
                            <td class="py-4 font-bold text-slate-300">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                            <td class="py-4 text-slate-400">{{ $tx->created_at->format('d M Y H:i') }}</td>
                            <td class="py-4">
                                @if($tx->payment_status === 'Pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-950 text-yellow-400 border border-yellow-800/30">Pending</span>
                                @elseif($tx->payment_status === 'Success')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-950 text-green-400 border border-green-800/30">Success</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-950 text-red-400 border border-red-800/30">Failed</span>
                                @endif
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($tx->payment_proof)
                                        <a href="{{ asset('storage/' . $tx->payment_proof) }}" target="_blank" class="px-2.5 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-colors">
                                            <i class="fa-solid fa-image mr-1"></i> Bukti
                                        </a>
                                    @endif

                                    @if($tx->payment_status === 'Pending')
                                        <form method="POST" action="{{ route('kasir.transactions.verify', $tx->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="Success">
                                            <button type="submit" class="px-2.5 py-1.5 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-colors">
                                                Verifikasi
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('kasir.transactions.verify', $tx->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="Failed">
                                            <button type="submit" class="px-2.5 py-1.5 rounded bg-red-650 hover:bg-red-600 text-white text-xs font-bold transition-colors">
                                                Tolak
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                <i class="fa-solid fa-wallet text-3xl mb-2 block"></i>
                                Belum ada aktivitas transaksi masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
