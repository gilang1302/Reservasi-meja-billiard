@extends('layouts.app')

@section('title', 'Inventaris Alat')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-boxes-stacked text-[#6C3BFF]"></i> Manajemen Inventaris Alat
            </h2>
            <p class="text-sm text-slate-400 mt-1">Daftar stik billiard premium, set bola turnamen, dan kelengkapan aksesoris sewa lainnya.</p>
        </div>
        <a href="{{ route('owner.inventory.create') }}" class="px-4 py-2.5 rounded-xl bg-[#6C3BFF] hover:bg-indigo-650 text-white text-xs font-bold shadow-lg shadow-purple-900/40 flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-plus"></i> Tambah Item Baru
        </a>
    </div>

    <!-- Inventory Table Card -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800/80">
                        <th class="pb-3 font-semibold w-24">Item ID</th>
                        <th class="pb-3 font-semibold">Nama Barang</th>
                        <th class="pb-3 font-semibold">Kategori</th>
                        <th class="pb-3 font-semibold">Ketersediaan Stok</th>
                        <th class="pb-3 font-semibold">Tarif Sewa / Jam</th>
                        <th class="pb-3 font-semibold">Harga Beli Aset</th>
                        <th class="pb-3 font-semibold">Kondisi</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($inventory as $item)
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 font-mono font-bold text-slate-400">#{{ $item->id }}</td>
                            <td class="py-4 font-semibold text-slate-200">{{ $item->item_name }}</td>
                            <td class="py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-450 border border-slate-700/50">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="py-4 font-semibold text-slate-300">{{ $item->quantity_available }} / {{ $item->quantity }} pcs</td>
                            <td class="py-4 font-bold text-[#FF6B35]">
                                @if($item->rental_price_per_hour > 0)
                                    Rp {{ number_format($item->rental_price_per_hour, 0, ',', '.') }}/jam
                                @else
                                    <span class="text-emerald-450 font-extrabold uppercase text-[10px]">Gratis</span>
                                @endif
                            </td>
                            <td class="py-4 text-slate-400">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase
                                    {{ $item->status === 'Good' ? 'bg-green-500/10 text-green-400' : '' }}
                                    {{ $item->status === 'Damaged' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                    {{ $item->status === 'Lost' ? 'bg-red-500/10 text-red-400' : '' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('owner.inventory.edit', $item->id) }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-colors" title="Edit Item">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('owner.inventory.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item inventaris ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-red-950/40 hover:bg-red-900 text-red-400 text-xs font-bold transition-colors" title="Hapus Item">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500">
                                Belum ada item inventaris tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
