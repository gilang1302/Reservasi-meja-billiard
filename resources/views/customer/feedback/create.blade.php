@extends('layouts.app')

@section('title', 'Tulis Umpan Balik')

@section('content')
<div class="max-w-xl mx-auto space-y-8" x-data="{ rating: 5 }">
    <!-- Header -->
    <div>
        <a href="{{ route('booking.history') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 mb-2 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
        </a>
        <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-star-half-stroke text-[#6C3BFF]"></i> Berikan Umpan Balik
        </h2>
        <p class="text-sm text-slate-400 mt-1">Bagikan pengalaman bermain Anda di Meja #{{ $booking->table->table_number }} untuk membantu kami meningkatkan layanan.</p>
    </div>

    <!-- Feedback Form Card -->
    <div class="glass-panel rounded-3xl p-8 border border-slate-800/80 shadow-xl">
        <form method="POST" action="{{ route('feedback.store', $booking->id) }}" class="space-y-6">
            @csrf

            <!-- Session Recap Summary -->
            <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-850 text-xs space-y-1">
                <span class="block font-bold text-slate-400 uppercase tracking-wider">Ringkasan Sesi</span>
                <p class="text-slate-200">Meja #{{ $booking->table->table_number }} &bull; {{ $booking->duration_hours }} Jam bermain &bull; Selesai {{ $booking->end_time->diffForHumans() }}</p>
            </div>

            <!-- Star Rating selector -->
            <div class="space-y-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 text-center">Berapa bintang yang ingin Anda berikan?</label>
                <input type="hidden" name="rating" :value="rating">
                
                <div class="flex items-center justify-center gap-3 py-4">
                    <template x-for="i in 5">
                        <button type="button" 
                                @click="rating = i" 
                                class="text-4xl transition-all duration-150 transform hover:scale-115 focus:outline-none"
                                :class="i <= rating ? 'text-yellow-400' : 'text-slate-700 hover:text-yellow-500'">
                            <i class="fa-solid fa-star"></i>
                        </button>
                    </template>
                </div>
                
                <p class="text-xs text-center font-bold text-yellow-400 uppercase tracking-widest" 
                   x-text="rating === 5 ? 'Sempurna!' : (rating === 4 ? 'Sangat Baik' : (rating === 3 ? 'Cukup Baik' : (rating === 2 ? 'Buruk' : 'Sangat Buruk')))">
                </p>
            </div>

            <!-- Comment Input -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Tulis Umpan Balik / Komentar</label>
                <textarea name="comment" rows="4" required placeholder="Tulis masukan Anda mengenai kondisi meja, kebersihan hall, atau keramahan kasir..."
                          class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0 resize-none"></textarea>
            </div>

            <!-- Submit button -->
            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#6C3BFF] to-indigo-600 hover:from-indigo-600 hover:to-[#6C3BFF] text-white text-sm font-bold shadow-lg shadow-purple-900/40 flex items-center justify-center gap-1.5 transition-all duration-300">
                Kirim Feedback <i class="fa-solid fa-paper-plane ml-1"></i>
            </button>
        </form>
    </div>
</div>
@endsection
