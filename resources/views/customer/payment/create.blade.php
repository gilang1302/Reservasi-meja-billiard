@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran')

@section('content')
<div class="max-w-xl mx-auto space-y-8" x-data="{ method: 'BCA' }">
    <!-- Header -->
    <div>
        <a href="{{ route('booking.show', $booking->id) }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 mb-2 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Rincian
        </a>
        <h2 class="text-2xl font-bold text-slate-100">Simulasi Pembayaran Digital</h2>
        <p class="text-sm text-slate-400 mt-1">Gunakan formulir ini untuk mensimulasikan pembayaran digital Anda.</p>
    </div>

    <!-- Payment form card -->
    <div class="glass-panel rounded-3xl p-8 border border-slate-800/80 shadow-2xl relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-36 h-36 bg-orange-500/5 rounded-full blur-2xl pointer-events-none"></div>

        <form method="POST" action="{{ route('transaction.pay', $booking->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Payment Summary -->
            <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-850 space-y-3">
                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">Jumlah Tagihan</span>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-extrabold text-[#FF6B35]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    <span class="text-xs text-slate-400">Booking ID: #{{ $booking->id }}</span>
                </div>
            </div>

            <!-- Payment Method Selector -->
            <div class="space-y-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Metode Pembayaran</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex flex-col items-center justify-center p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                           :class="method === 'BCA' ? 'border-[#6C3BFF] bg-[#6C3BFF]/5 text-white' : 'border-slate-800 bg-slate-900/40 text-slate-400 hover:border-slate-700'">
                        <input type="radio" name="payment_method" value="BCA" class="hidden" @click="method = 'BCA'" checked>
                        <i class="fa-solid fa-building-columns text-lg mb-2"></i>
                        <span class="text-xs font-bold">Transfer BCA</span>
                    </label>

                    <label class="flex flex-col items-center justify-center p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                           :class="method === 'QRIS' ? 'border-[#6C3BFF] bg-[#6C3BFF]/5 text-white' : 'border-slate-800 bg-slate-900/40 text-slate-400 hover:border-slate-700'">
                        <input type="radio" name="payment_method" value="QRIS" class="hidden" @click="method = 'QRIS'">
                        <i class="fa-solid fa-qrcode text-lg mb-2"></i>
                        <span class="text-xs font-bold">QRIS Code</span>
                    </label>

                    <label class="flex flex-col items-center justify-center p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                           :class="method === 'Tunai' ? 'border-[#6C3BFF] bg-[#6C3BFF]/5 text-white' : 'border-slate-800 bg-slate-900/40 text-slate-400 hover:border-slate-700'">
                        <input type="radio" name="payment_method" value="Tunai" class="hidden" @click="method = 'Tunai'">
                        <i class="fa-solid fa-coins text-lg mb-2"></i>
                        <span class="text-xs font-bold">Bayar Kasir</span>
                    </label>
                </div>
            </div>

            <!-- Transfer bank details (for BCA) -->
            <div x-show="method === 'BCA'" class="p-5 rounded-2xl bg-slate-950/70 border border-slate-900 space-y-4 transition-all duration-200">
                <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wide border-b border-slate-900 pb-2">Instruksi Transfer BCA</h4>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nama Penerima</span>
                        <span class="font-bold text-slate-200">CueMaster Reserve</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nomor Rekening</span>
                        <span class="font-extrabold text-blue-400 tracking-wider">123-456-7890</span>
                    </div>
                </div>
            </div>

            <!-- QRIS Code Image placeholder (for QRIS) -->
            <div x-show="method === 'QRIS'" class="p-5 rounded-2xl bg-slate-950/70 border border-slate-900 space-y-4 flex flex-col items-center transition-all duration-200">
                <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wide border-b border-slate-900 pb-2 w-full text-center">Scan QRIS CueMaster</h4>
                <!-- Simulate a QRIS Code using CSS/HTML block -->
                <div class="w-36 h-36 bg-white p-2 rounded-xl flex items-center justify-center border border-slate-700">
                    <div class="w-full h-full bg-slate-200 flex flex-col items-center justify-center text-slate-800 text-[10px] font-bold gap-1 text-center">
                        <i class="fa-solid fa-qrcode text-3xl"></i>
                        <span>QRIS SIMULATOR</span>
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 text-center">Silakan scan kode QR di atas menggunakan dompet digital Anda (GoPay, OVO, Dana, LinkAja, Mobile Banking).</p>
            </div>

            <!-- Pay Kasir description -->
            <div x-show="method === 'Tunai'" class="p-5 rounded-2xl bg-slate-950/70 border border-slate-900 text-xs text-slate-400 space-y-2">
                <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wide border-b border-slate-900 pb-2">Pembayaran Tunai di Kasir</h4>
                <p>Silakan temui Operator Kasir CueMaster untuk melakukan pembayaran tunai. Tunjukkan Booking ID Anda <strong class="text-slate-200">#{{ $booking->id }}</strong>.</p>
            </div>

            <!-- Upload Bukti Transfer -->
            <div x-show="method !== 'Tunai'" class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Unggah Bukti Transfer / Pembayaran</label>
                <div class="relative w-full border border-dashed border-slate-800 rounded-xl p-4 bg-slate-950/20 text-center hover:bg-slate-950/40 transition-colors">
                    <input type="file" name="payment_proof" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                    <div class="space-y-1.5 pointer-events-none">
                        <i class="fa-solid fa-file-image text-2xl text-slate-600"></i>
                        <span class="block text-xs text-slate-400">Pilih berkas gambar bukti transfer</span>
                        <span class="block text-[10px] text-slate-500">(Format JPG, PNG max 2MB)</span>
                    </div>
                </div>
            </div>

            <!-- Submit button -->
            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#FF6B35] to-orange-600 hover:from-orange-600 hover:to-[#FF6B35] text-white text-sm font-bold shadow-lg shadow-orange-950/40 flex items-center justify-center gap-1.5 transition-all duration-300">
                <i class="fa-solid fa-check"></i> Konfirmasi & Kirim Pembayaran
            </button>
        </form>
    </div>
</div>
@endsection
