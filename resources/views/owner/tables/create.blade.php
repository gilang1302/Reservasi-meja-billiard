@extends('layouts.app')

@section('title', 'Tambah Meja Baru')

@section('content')
<div class="max-w-xl mx-auto space-y-8">
    <!-- Header -->
    <div>
        <a href="{{ route('owner.tables.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 mb-2 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Pengaturan
        </a>
        <h2 class="text-2xl font-bold text-slate-100">Tambah Meja Billiard Baru</h2>
        <p class="text-sm text-slate-400 mt-1">Lengkapi formulir di bawah untuk menambahkan meja baru ke dalam hall.</p>
    </div>

    <!-- Form Card -->
    <div class="glass-panel rounded-3xl p-8 border border-slate-800/80 shadow-2xl">
        <form method="POST" action="{{ route('owner.tables.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nomor Meja</label>
                    <input type="text" name="table_number" required placeholder="Contoh: 9, V3, T3"
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Tipe Meja</label>
                    <select name="table_type" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                        <option value="Standard">Standard</option>
                        <option value="VIP">VIP</option>
                        <option value="Tournament">Tournament</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Harga Off-Peak / Jam</label>
                    <input type="number" name="price_per_hour" required placeholder="30000" min="0"
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Harga Peak Hour / Jam</label>
                    <input type="number" name="price_peak_per_hour" required placeholder="45000" min="0"
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Posisi Kolom (X)</label>
                    <input type="number" name="position_x" required placeholder="1-4" min="1"
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Posisi Baris (Y)</label>
                    <input type="number" name="position_y" required placeholder="1-3" min="1"
                           class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi & Fasilitas</label>
                <textarea name="description" rows="3" placeholder="Contoh: AC dingin, sofa empuk, bola Aramith..."
                          class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0 resize-none"></textarea>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#6C3BFF] to-indigo-600 hover:from-indigo-600 hover:to-[#6C3BFF] text-white text-sm font-bold shadow-lg shadow-purple-900/40 flex items-center justify-center gap-1.5 transition-all duration-300">
                <i class="fa-solid fa-plus"></i> Tambah Meja Baru
            </button>
        </form>
    </div>
</div>
@endsection
