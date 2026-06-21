@extends('layouts.app')

@section('title', 'Laporan Pendapatan')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-file-invoice-dollar text-[#6C3BFF]"></i> Laporan Pendapatan Bisnis
            </h2>
            <p class="text-sm text-slate-400 mt-1">Laporan finansial berdasarkan Template Method Pattern. Pilih filter periode untuk menganalisis data.</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700/60 flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-print"></i> Cetak / Ekspor PDF
        </button>
    </div>

    <!-- Filter Periode -->
    <div class="p-6 rounded-3xl glass-panel border border-slate-800/80">
        <form method="GET" action="{{ route('owner.reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Tipe Laporan</label>
                <select name="period" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
                    <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Harian (DailyReport)</option>
                    <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Mingguan (WeeklyReport)</option>
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan (MonthlyReport)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Tanggal Mulai</label>
                <input type="date" name="from" value="{{ $from }}" required
                       class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Tanggal Selesai</label>
                <input type="date" name="to" value="{{ $to }}" required
                       class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-slate-200 text-sm focus:border-[#6C3BFF] focus:ring-0">
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-[#6C3BFF] hover:bg-indigo-650 text-white text-sm font-bold shadow-lg shadow-purple-900/40 transition-all duration-300">
                    <i class="fa-solid fa-filter mr-1"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Total Pendapatan Bersih</span>
            <span class="block text-2xl font-extrabold text-[#FF6B35] mt-2">Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</span>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Jumlah Game Dimainkan</span>
            <span class="block text-2xl font-extrabold text-blue-400 mt-2">{{ $data['total_bookings'] }} Sesi</span>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Rata-Rata Transaksi</span>
            <span class="block text-2xl font-extrabold text-slate-200 mt-2">Rp {{ number_format($data['avg_ticket'], 0, ',', '.') }}</span>
        </div>

        <div class="p-5 rounded-2xl glass-panel border border-slate-805 flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Rata-Rata Rating Kepuasan</span>
            <span class="block text-2xl font-extrabold text-yellow-400 mt-2 flex items-center gap-1">
                <i class="fa-solid fa-star text-sm"></i> {{ $data['avg_rating'] }} / 5.0
            </span>
        </div>
    </div>

    <!-- Chart & Table Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Period graph (2/3 width) -->
        <div class="lg:col-span-2 glass-panel rounded-3xl p-6 border border-slate-800/80">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-6 border-b border-slate-805 pb-3">Grafik Pendapatan</h3>
            <div class="h-80 w-full">
                <canvas id="periodRevenueChart"></canvas>
            </div>
        </div>

        <!-- Top tables list (1/3 width) -->
        <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-805 pb-3">Kinerja Meja Terbaik</h3>
            
            <div class="space-y-4">
                @forelse($data['top_tables'] as $index => $item)
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-900/60 border border-slate-850">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-slate-800 border border-slate-700/50 flex items-center justify-center text-xs font-bold text-slate-300">
                                #{{ $index + 1 }}
                            </span>
                            <div>
                                <span class="block font-bold text-xs text-slate-200">Meja {{ $item['table_number'] }}</span>
                                <span class="block text-[9px] text-slate-500">{{ $item['count'] }} Sesi Sewa</span>
                            </div>
                        </div>
                        <span class="text-xs font-extrabold text-[#FF6B35]">Rp {{ number_format($item['revenue'], 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 py-4 text-center">Belum ada data kinerja meja</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- History Transactions details table -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800/80">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-805 pb-3">Histori Transaksi Periode</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800/80">
                        <th class="pb-3 font-semibold">Transaksi ID</th>
                        <th class="pb-3 font-semibold">Pelanggan</th>
                        <th class="pb-3 font-semibold">Meja Billiard</th>
                        <th class="pb-3 font-semibold">Metode</th>
                        <th class="pb-3 font-semibold">Tanggal & Waktu</th>
                        <th class="pb-3 font-semibold text-right">Jumlah Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($data['transactions'] as $tx)
                        <tr class="hover:bg-slate-900/10 transition-colors">
                            <td class="py-3 font-mono font-bold text-slate-300">#{{ $tx->id }}</td>
                            <td class="py-3 font-semibold text-slate-200">{{ $tx->booking->user->name ?? 'N/A' }}</td>
                            <td class="py-3 text-slate-300">Meja {{ $tx->booking->table->table_number ?? 'N/A' }}</td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-400">
                                    {{ $tx->payment_method }}
                                </span>
                            </td>
                            <td class="py-3 text-slate-400 text-xs">{{ $tx->created_at->format('d M Y H:i') }} WIB</td>
                            <td class="py-3 text-right font-extrabold text-[#FF6B35]">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                Belum ada transaksi tercatat dalam periode filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('periodRevenueChart').getContext('2d');
        
        const chartData = @json($data['chart_data']);
        const labels = chartData.map(d => d.label);
        const revenue = chartData.map(d => d.revenue);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendapatan Bersih (Rp)',
                        data: revenue,
                        backgroundColor: '#6C3BFF',
                        hoverBackgroundColor: '#FF6B35',
                        borderRadius: 8,
                        borderWidth: 0
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
                        grid: { color: 'rgba(255, 255, 255, 0.03)' },
                        ticks: { color: '#94a3b8', font: { family: 'Outfit' } }
                    }
                }
            }
        });
    });
</script>
@endsection
