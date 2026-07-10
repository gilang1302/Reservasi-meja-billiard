@extends('layouts.app')

@section('title', 'Leaderboard & Gamifikasi')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-trophy text-[#6C3BFF]"></i> Klasemen Pelanggan (Leaderboard)
            </h2>
            <p class="text-sm text-slate-400 mt-1">Kumpulkan poin bermain dan naikkkan peringkat Anda untuk mendapatkan keuntungan lebih besar!</p>
        </div>
        
        <!-- User Status Badge -->
        @if(auth()->user()->isPelanggan())
            @php
                $eligibility = \App\Factories\MemberFactory::checkUpgradeEligibility(auth()->user());
            @endphp
            <div class="p-4 rounded-2xl glass-panel border border-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-850 border border-slate-700/60 flex items-center justify-center text-xl text-yellow-400 shadow">
                    🥇
                </div>
                <div class="text-xs">
                    <span class="text-slate-400 block font-semibold">Peringkat Anda</span>
                    <span class="font-extrabold text-slate-200">#{{ $myRank }} &bull; {{ auth()->user()->member_poin }} PTS</span>
                    @if($eligibility['next_tier'])
                        <span class="block text-[10px] text-slate-500 mt-0.5">Butuh {{ $eligibility['points_needed'] }} PTS lagi ke tier {{ $eligibility['next_tier'] }}</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Member Benefits Tier Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($allBenefits as $tier => $benefit)
            @php
                $isActiveTier = auth()->user()->member_type === $tier;
            @endphp
            <div class="p-6 rounded-3xl relative overflow-hidden flex flex-col justify-between border {{ $isActiveTier ? 'border-[#6C3BFF] bg-[#6C3BFF]/5 glow-purple' : 'border-slate-800 bg-slate-900/40' }} transition-all duration-300">
                @if($isActiveTier)
                    <span class="absolute top-4 right-4 px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-[#6C3BFF]/20 text-[#6C3BFF] border border-[#6C3BFF]/30">Tier Anda</span>
                @endif
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">{{ $benefit['icon'] }}</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-100" style="color: {{ $benefit['color'] }}">{{ $tier }}</h3>
                            <span class="text-[10px] text-slate-400 block font-medium">{{ $benefit['description'] }}</span>
                        </div>
                    </div>

                    <div class="space-y-2 text-xs pt-2">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Potongan Harga</span>
                            <span class="font-bold text-slate-200">{{ $benefit['discount'] * 100 }}% OFF</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Prioritas Antrian</span>
                            <span class="font-bold text-slate-200">Level {{ $benefit['priority'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Akses Meja VIP</span>
                            <span class="font-bold text-slate-200">{{ $benefit['vip_access'] ? 'Ya (Bisa)' : 'Tidak' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Multi Booking Limit</span>
                            <span class="font-bold text-slate-200">{{ $benefit['max_bookings'] }} Meja</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Leaderboard Table -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <h3 class="text-md font-bold mb-4 border-b border-slate-800 pb-3 uppercase tracking-wider text-slate-400">Klasemen 50 Besar</h3>
        
        <!-- Podium Top 3 (if exists) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 pt-4">
            
            <!-- Podium 2 -->
            @if(isset($leaderboard[1]))
                <div class="p-5 rounded-2xl bg-slate-900/40 border border-slate-800 flex flex-col items-center justify-between text-center relative order-2 md:order-1">
                    <span class="absolute top-4 left-4 font-extrabold text-slate-500 text-sm">#2</span>
                    <div class="w-12 h-12 rounded-full bg-slate-700/30 border border-slate-600/50 flex items-center justify-center text-xl shadow mb-3">🥈</div>
                    <h4 class="text-sm font-bold text-slate-200 truncate max-w-[150px]">{{ $leaderboard[1]->name }}</h4>
                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-slate-800 text-slate-400 border border-slate-700/50 mt-1.5">{{ $leaderboard[1]->member_type ?? 'Bronze' }}</span>
                    <span class="text-xs font-extrabold text-blue-400 mt-2">{{ $leaderboard[1]->member_poin }} PTS</span>
                </div>
            @endif

            <!-- Podium 1 (Gold) -->
            @if(isset($leaderboard[0]))
                <div class="p-6 rounded-2xl bg-[#6C3BFF]/5 border border-yellow-500/20 glow-purple flex flex-col items-center justify-between text-center relative order-1 md:order-2 transform md:scale-105 shadow-xl shadow-purple-950/20">
                    <span class="absolute top-4 left-4 font-extrabold text-yellow-500 text-sm">#1</span>
                    <div class="w-14 h-14 rounded-full bg-yellow-500/20 border border-yellow-500/40 flex items-center justify-center text-2xl shadow mb-3 animate-bounce">👑</div>
                    <h4 class="text-md font-bold text-yellow-400 truncate max-w-[150px]">{{ $leaderboard[0]->name }}</h4>
                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-yellow-950 text-yellow-400 border border-yellow-800/30 mt-1.5">{{ $leaderboard[0]->member_type ?? 'Bronze' }}</span>
                    <span class="text-sm font-extrabold text-yellow-400 mt-2">{{ $leaderboard[0]->member_poin }} PTS</span>
                </div>
            @endif

            <!-- Podium 3 -->
            @if(isset($leaderboard[2]))
                <div class="p-5 rounded-2xl bg-slate-900/40 border border-slate-800 flex flex-col items-center justify-between text-center relative order-3">
                    <span class="absolute top-4 left-4 font-extrabold text-amber-700 text-sm">#3</span>
                    <div class="w-12 h-12 rounded-full bg-amber-900/20 border border-amber-900/40 flex items-center justify-center text-xl shadow mb-3">🥉</div>
                    <h4 class="text-sm font-bold text-slate-200 truncate max-w-[150px]">{{ $leaderboard[2]->name }}</h4>
                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-slate-800 text-slate-400 border border-slate-700/50 mt-1.5">{{ $leaderboard[2]->member_type ?? 'Bronze' }}</span>
                    <span class="text-xs font-extrabold text-blue-400 mt-2">{{ $leaderboard[2]->member_poin }} PTS</span>
                </div>
            @endif
        </div>

        <!-- Leaderboard Rest Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800/80">
                        <th class="pb-3 font-semibold w-16">Peringkat</th>
                        <th class="pb-3 font-semibold">Nama Pelanggan</th>
                        <th class="pb-3 font-semibold">Level Member</th>
                        <th class="pb-3 font-semibold">Total Jam Bermain</th>
                        <th class="pb-3 font-semibold text-right">Poin Loyalitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @foreach($leaderboard as $idx => $user)
                        <tr class="hover:bg-slate-900/10 transition-colors {{ auth()->id() === $user->id ? 'bg-purple-950/20 border-y border-purple-500/20' : '' }}">
                            <td class="py-3.5 font-bold text-slate-400">
                                @if($user->rank === 1)
                                    <span class="text-yellow-400">#1</span>
                                @elseif($user->rank === 2)
                                    <span class="text-slate-300">#2</span>
                                @elseif($user->rank === 3)
                                    <span class="text-amber-600">#3</span>
                                @else
                                    #{{ $user->rank }}
                                @endif
                            </td>
                            <td class="py-3.5">
                                <span class="font-bold text-slate-200">{{ $user->name }}</span>
                                @if(auth()->id() === $user->id)
                                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-[8px] font-bold bg-[#6C3BFF] text-white">Anda</span>
                                @endif
                            </td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold" 
                                      style="color: {{ $user->benefits['color'] }}; background: {{ $user->benefits['color'] }}15; border: 1px solid {{ $user->benefits['color'] }}30">
                                    {{ $user->member_type ?? 'Bronze' }}
                                </span>
                            </td>
                            <td class="py-3.5 text-slate-300 font-semibold">{{ $user->total_hours_played ?? 0 }} Jam</td>
                            <td class="py-3.5 text-right font-extrabold text-blue-400">{{ $user->member_poin }} PTS</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
