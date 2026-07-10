@extends('layouts.app')

@section('title', 'Owner Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-[#6C3BFF]"></i> Panel Owner Analytics
            </h2>
            <p class="text-sm text-slate-400 mt-1">Pantau performa bisnis, pendapatan harian, kegemaran meja, dan kepuasan pelanggan secara real-time.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('owner.reports') }}" class="px-4 py-2.5 rounded-xl bg-[#6C3BFF] hover:bg-indigo-650 text-white text-xs font-bold shadow-lg shadow-purple-900/40 flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-file-invoice-dollar"></i> Laporan Detail
            </a>
        </div>
    </div>

    <!-- Stats Matrix Grid -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Pendapatan Hari Ini</span>
            <span class="block text-xl font-extrabold text-[#FF6B35] mt-2">Rp {{ number_format($stats['total_revenue_today'], 0, ',', '.') }}</span>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Bulan Ini (Month-to-Date)</span>
            <span class="block text-xl font-extrabold text-blue-400 mt-2">Rp {{ number_format($stats['total_revenue_month'], 0, ',', '.') }}</span>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Pemesanan Hari Ini</span>
            <span class="block text-xl font-extrabold text-slate-200 mt-2">{{ $stats['total_bookings_today'] }} Reservasi</span>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Total Pelanggan</span>
            <span class="block text-xl font-extrabold text-purple-400 mt-2">{{ $stats['total_customers'] }} Akun</span>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Rating Kepuasan</span>
            <span class="block text-xl font-extrabold text-yellow-400 mt-2 flex items-center gap-1.5">
                <i class="fa-solid fa-star text-sm"></i> {{ round($stats['avg_rating'], 1) }} / 5.0
            </span>
        </div>
    </div>

    <!-- Revenue Graph & Heatmap Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Past 7 Days Revenue Trend (2/3 width) -->
        <div class="lg:col-span-2 glass-panel rounded-3xl p-6 border border-slate-800/80">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-6 border-b border-slate-805 pb-3">Tren Pendapatan & Booking (7 Hari Terakhir)</h3>
            <div class="h-80 w-full">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>

        <!-- Table Heatmap Summary (1/3 width) -->
        <div class="glass-panel rounded-3xl p-6 border border-slate-800/80 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-805 pb-3">Popularitas Meja (Usage Count)</h3>
                
                <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                    @foreach($heatmapData->sortByDesc('usage_count')->take(5) as $table)
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-300">Meja {{ $table->table_number }} ({{ $table->table_type }})</span>
                                <span class="text-slate-400 font-bold">{{ $table->usage_count }} Sesi</span>
                            </div>
                            <div class="w-full h-2 bg-slate-900 border border-slate-850 rounded-full overflow-hidden">
                                @php
                                    $maxUsage = $heatmapData->max('usage_count') ?: 1;
                                    $pct = ($table->usage_count / $maxUsage) * 100;
                                @endphp
                                <div class="h-full bg-gradient-to-r from-[#6C3BFF] to-indigo-500 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('owner.tables.heatmap') }}" class="w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700/60 flex items-center justify-center gap-1.5 transition-colors mt-4">
                <i class="fa-solid fa-fire text-orange-500"></i> Lihat Heatmap Denah
            </a>
        </div>
    </div>

    <!-- Recent reviews & feedbacks -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <div class="flex items-center justify-between border-b border-slate-805 pb-3 mb-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Masukan & Review Terbaru Pelanggan</h3>
            <a href="{{ route('owner.feedback.index') }}" class="text-xs text-[#6C3BFF] hover:underline font-semibold">Semua Umpan Balik</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($recentFeedbacks as $fb)
                <div class="p-5 rounded-2xl bg-slate-900/40 border border-slate-850 flex flex-col justify-between gap-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="block font-bold text-slate-200 text-sm">{{ $fb->user->name }}</span>
                            <span class="text-[10px] text-slate-500">Bermain di Meja #{{ $fb->booking->table->table_number ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="flex items-center text-yellow-400 gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-{{ $i <= $fb->rating ? 'solid' : 'regular' }} fa-star text-[10px]"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 italic">"{{ $fb->comment }}"</p>
                    <span class="text-[9px] text-slate-500 self-end">{{ $fb->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div class="col-span-2 text-center py-8 text-slate-500 text-xs">
                    Belum ada ulasan yang masuk.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Chart JS Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueTrendChart').getContext('2d');
        
        const chartData = @json($revenueChart);
        const labels = chartData.map(d => d.date);
        const revenue = chartData.map(d => d.revenue);
        const bookings = chartData.map(d => d.bookings);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendapatan (Rp)',
                        data: revenue,
                        borderColor: '#6C3BFF',
                        backgroundColor: 'rgba(108, 59, 255, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Reservasi',
                        data: bookings,
                        borderColor: '#FF6B35',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#94a3b8',
                            font: { family: 'Outfit' }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.03)' },
                        ticks: { color: '#94a3b8', font: { family: 'Outfit' } }
                    },
                    y: {
                        position: 'left',
                        grid: { color: 'rgba(255, 255, 255, 0.03)' },
                        ticks: { color: '#94a3b8', font: { family: 'Outfit' } },
                        yAxisID: 'y'
                    },
                    y1: {
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { color: '#94a3b8', font: { family: 'Outfit' } },
                        yAxisID: 'y1'
                    }
                }
            }
        });
    });
</script>
@endsection
