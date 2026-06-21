@extends('layouts.app')

@section('title', 'Reservasi Masuk')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-receipt text-[#6C3BFF]"></i> Daftar Reservasi Pelanggan
        </h2>
        <p class="text-sm text-slate-400 mt-1">Konfirmasi reservasi pelanggan, pantau status waktu, dan saring jadwal bermain.</p>
    </div>

    <!-- Bookings Table -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800/80">
                        <th class="pb-3 font-semibold">ID Booking</th>
                        <th class="pb-3 font-semibold">Pelanggan</th>
                        <th class="pb-3 font-semibold">Meja</th>
                        <th class="pb-3 font-semibold">Jadwal Bermain</th>
                        <th class="pb-3 font-semibold">Total Biaya</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 font-bold text-slate-300">#{{ $booking->id }}</td>
                            <td class="py-4">
                                <span class="block font-semibold">{{ $booking->user->name }}</span>
                                <span class="text-xs text-slate-500">{{ $booking->user->phone ?? 'Tidak ada telepon' }}</span>
                            </td>
                            <td class="py-4">
                                <span class="block font-semibold">Meja {{ $booking->table->table_number }}</span>
                                <span class="text-xs text-slate-500">{{ $booking->table->table_type }}</span>
                            </td>
                            <td class="py-4">
                                <span class="block text-slate-300">{{ $booking->start_time->format('d M Y') }}</span>
                                <span class="text-xs text-slate-500">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }} ({{ $booking->duration_hours }} Jam)</span>
                            </td>
                            <td class="py-4 font-semibold text-slate-300">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td class="py-4">
                                @if($booking->status === 'Pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-950 text-yellow-400 border border-yellow-800/30">Pending</span>
                                @elseif($booking->status === 'Confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-950 text-blue-400 border border-blue-800/30">Confirmed</span>
                                @elseif($booking->status === 'Active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-950 text-purple-400 border border-purple-800/30">Active</span>
                                @elseif($booking->status === 'Completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-950 text-green-400 border border-green-800/30">Completed</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-950 text-red-400 border border-red-800/30">Cancelled</span>
                                @endif
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($booking->status === 'Pending')
                                        <form method="POST" action="{{ route('kasir.bookings.confirm', $booking->id) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-colors">
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                <i class="fa-regular fa-calendar-times text-3xl mb-2 block"></i>
                                Belum ada aktivitas reservasi masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
