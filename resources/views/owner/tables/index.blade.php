@extends('layouts.app')

@section('title', 'Daftar Meja Billiard')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-square-poll-horizontal text-[#6C3BFF]"></i> Pengaturan Meja Billiard
            </h2>
            <p class="text-sm text-slate-400 mt-1">Kelola meja billiard di hall, atur skema harga normal & peak hour, dan tentukan letak koordinat denah.</p>
        </div>
        <a href="{{ route('owner.tables.create') }}" class="px-4 py-2.5 rounded-xl bg-[#6C3BFF] hover:bg-indigo-650 text-white text-xs font-bold shadow-lg shadow-purple-900/40 flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-plus"></i> Tambah Meja Baru
        </a>
    </div>

    <!-- Tables CRUD Table -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800/80">
                        <th class="pb-3 font-semibold w-24">Nomor Meja</th>
                        <th class="pb-3 font-semibold">Tipe Meja</th>
                        <th class="pb-3 font-semibold">Harga Off-Peak</th>
                        <th class="pb-3 font-semibold">Harga Peak Hour</th>
                        <th class="pb-3 font-semibold">Posisi Koordinat (X, Y)</th>
                        <th class="pb-3 font-semibold">Status Operasional</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($tables as $table)
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 font-bold text-slate-200">Meja {{ $table->table_number }}</td>
                            <td class="py-4 font-semibold text-slate-350">{{ $table->table_type }}</td>
                            <td class="py-4 font-bold text-emerald-400">Rp {{ number_format($table->price_per_hour, 0, ',', '.') }}</td>
                            <td class="py-4 font-bold text-orange-400">Rp {{ number_format($table->price_peak_per_hour, 0, ',', '.') }}</td>
                            <td class="py-4 text-slate-400">Kolom {{ $table->position_x }}, Baris {{ $table->position_y }}</td>
                            <td class="py-4">
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase
                                    {{ $table->status === 'Available' ? 'bg-emerald-500/10 text-emerald-400' : '' }}
                                    {{ $table->status === 'Occupied' ? 'bg-red-500/10 text-red-400' : '' }}
                                    {{ $table->status === 'Booked' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                    {{ $table->status === 'Maintenance' ? 'bg-slate-800 text-slate-550' : '' }}">
                                    {{ $table->status }}
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('owner.tables.edit', $table->id) }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-colors" title="Edit Meja">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('owner.tables.destroy', $table->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus meja ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-red-950/40 hover:bg-red-900 text-red-400 text-xs font-bold transition-colors" title="Hapus Meja">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                Tidak ada meja terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
