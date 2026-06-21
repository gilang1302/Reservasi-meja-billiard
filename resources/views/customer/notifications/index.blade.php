@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
                <i class="fa-regular fa-bell text-[#6C3BFF]"></i> Semua Notifikasi
            </h2>
            <p class="text-sm text-slate-400 mt-1">Kelola seluruh pesan masuk, pengingat durasi, dan status transaksi Anda.</p>
        </div>

        @php
            $unreadCount = auth()->user()->unread_notifications_count;
        @endphp
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold border border-slate-700/60 transition-colors">
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List Card -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <div class="divide-y divide-slate-805">
            @forelse($notifications as $notif)
                <div class="py-4 first:pt-0 last:pb-0 flex items-start gap-4 {{ !$notif->is_read ? 'bg-[#6C3BFF]/2 p-3 rounded-xl border border-[#6C3BFF]/10' : '' }}">
                    <div class="w-10 h-10 rounded-xl bg-slate-850 border border-slate-800 flex items-center justify-center text-lg">
                        @if($notif->type === 'fnb_ready') 🍔 @elseif($notif->type === 'payment') 💳 @else ⏰ @endif
                    </div>
                    
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-bold text-slate-200">{{ $notif->title }}</h4>
                            <span class="text-[10px] text-slate-500 font-semibold">{{ $notif->sent_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed">{{ $notif->message }}</p>
                        
                        @if(!$notif->is_read)
                            <form method="POST" action="{{ route('notifications.read', $notif->id) }}" class="pt-1.5">
                                @csrf
                                <button type="submit" class="text-[10px] text-[#FF6B35] hover:underline font-extrabold flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i> Tandai telah dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-slate-500 space-y-3">
                    <i class="fa-regular fa-bell-slash text-4xl block text-slate-655"></i>
                    <p class="text-sm">Kotak masuk notifikasi Anda bersih!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
