@extends('layouts.app')

@section('title', 'Edit Inventaris')

@section('content')
<div class="max-w-xl mx-auto space-y-8">
    <!-- Header -->
    <div>
        <a href="{{ route('owner.inventory.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 mb-2 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Inventaris
        </a>
        <h2 class="text-2xl font-bold text-slate-100">Edit Item Inventaris #{{ $inventory->id }}</h2>
        <p class="text-sm text-slate-400 mt-1">Perbarui kuantitas, harga sewa, atau status kondisi fisik barang.</p>
    </div>

    <!-- Form Card -->
    <div class="glass-panel rounded-3xl p-8 border border-slate-800/80 shadow-2xl">
        <form method="POST" action="{{ route('owner.inventory.update', $inventory->id) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nama Barang / Aset</label>
                <input type="text" name="item_name" value="{{ $inventory->item_name }}" required placeholder="Contoh: Stik Carbon Predator Revo"
                       class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kategori</label>
                    <select name="category" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                        <option value="Cue" {{ $inventory->category === 'Cue' ? 'selected' : '' }}>Cue (Stik)</option>
                        <option value="Ball" {{ $inventory->category === 'Ball' ? 'selected' : '' }}>Ball (Set Bola)</option>
                        <option value="Chalk" {{ $inventory->category === 'Chalk' ? 'selected' : '' }}>Chalk (Kapur)</option>
                        <option value="Rack" {{ $inventory->category === 'Rack' ? 'selected' : '' }}>Rack (Segitiga)</option>
                        <option value="Accessory" {{ $inventory->category === 'Accessory' ? 'selected' : '' }}>Aksesoris Lain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kondisi</label>
                    <select name="status" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                        <option value="Good" {{ $inventory->status === 'Good' ? 'selected' : '' }}>Good (Baik)</option>
                        <option value="Damaged" {{ $inventory->status === 'Damaged' ? 'selected' : '' }}>Damaged (Rusak)</option>
                        <option value="Lost" {{ $inventory->status === 'Lost' ? 'selected' : '' }}>Lost (Hilang)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Total Kuantitas</label>
                    <input type="number" name="quantity" value="{{ $inventory->quantity }}" required placeholder="5" min="1"
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Ketersediaan Sekarang</label>
                    <input type="number" name="quantity_available" value="{{ $inventory->quantity_available }}" required placeholder="5" min="0"
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Tarif Sewa / Jam</label>
                    <input type="number" name="rental_price_per_hour" value="{{ (int)$inventory->rental_price_per_hour }}" required placeholder="10000" min="0"
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Harga Beli Aset (Rp)</label>
                    <input type="number" name="price" value="{{ (int)$inventory->price }}" required placeholder="1500000" min="0"
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Catatan Aset</label>
                <textarea name="notes" rows="3" placeholder="Contoh: Aset dibeli Jan 2026, bersihkan berkala..."
                          class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0 resize-none">{{ $inventory->notes }}</textarea>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#6C3BFF] to-indigo-600 hover:from-indigo-600 hover:to-[#6C3BFF] text-white text-sm font-bold shadow-lg shadow-purple-900/40 flex items-center justify-center gap-1.5 transition-all duration-300">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
