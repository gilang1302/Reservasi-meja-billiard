@extends('layouts.app')

@section('title', 'Feedback Pelanggan')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-star-half-stroke text-[#6C3BFF]"></i> Umpan Balik & Masukan Pelanggan
        </h2>
        <p class="text-sm text-slate-400 mt-1">Review kepuasan pelanggan terhadap meja, pelayanan, dan F&B CueMaster.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Average rating card -->
        <div class="p-6 rounded-3xl glass-panel border border-slate-800 flex flex-col justify-between items-center text-center h-48">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Rata-Rata Rating</span>
                <span class="block text-4xl font-extrabold text-yellow-400 mt-2">{{ round($avgRating, 1) }} / 5.0</span>
            </div>
            
            <div class="flex items-center text-yellow-400 gap-1 text-lg">
                @for($i=1; $i<=5; $i++)
                    <i class="fa-{{ $i <= round($avgRating) ? 'solid' : 'regular' }} fa-star"></i>
                @endfor
            </div>
        </div>

        <!-- Rating Distribution card -->
        <div class="md:col-span-2 p-6 rounded-3xl glass-panel border border-slate-800 flex flex-col justify-between h-48">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Distribusi Rating</span>
            
            <div class="space-y-2 mt-2">
                @for($stars=5; $stars>=1; $stars--)
                    @php
                        $count = $ratingDistribution[$stars] ?? 0;
                        $total = $feedbacks->total() ?: 1;
                        $pct = ($count / $total) * 100;
                    @endphp
                    <div class="flex items-center gap-3 text-xs">
                        <span class="w-8 text-right font-bold text-slate-400">{{ $stars }} ⭐</span>
                        <div class="flex-1 h-2 bg-slate-900 border border-slate-850 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="w-12 text-slate-400 font-bold text-right">{{ $count }} ulasan</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Feedbacks List Card -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 border-b border-slate-805 pb-3 mb-6">Semua Ulasan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($feedbacks as $fb)
                <div class="p-5 rounded-2xl bg-slate-900/40 border border-slate-850 flex flex-col justify-between gap-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="block font-bold text-slate-200 text-sm">{{ $fb->user->name }}</span>
                            <span class="text-[10px] text-slate-500">Bermain di Meja #{{ $fb->booking->table->table_number ?? 'N/A' }} (Booking ID: #{{ $fb->booking_id }})</span>
                        </div>
                        
                        <div class="flex items-center text-yellow-400 gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-{{ $i <= $fb->rating ? 'solid' : 'regular' }} fa-star text-[10px]"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 italic">"{{ $fb->comment }}"</p>
                    <span class="text-[9px] text-slate-500 self-end">{{ $fb->created_at->format('d M Y H:i') }} WIB</span>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 text-slate-500 text-xs">
                    <i class="fa-regular fa-star-half-stroke text-4xl mb-2 block"></i>
                    Belum ada ulasan dari pelanggan.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>
@endsection
