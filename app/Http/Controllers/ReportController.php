<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * ReportController — Laporan Pendapatan
 * Template Method Pattern digunakan untuk generate laporan
 */
class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily');
        $from   = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to     = $request->get('to', now()->format('Y-m-d'));

        $data = $this->generateReport($period, $from, $to);

        return view('owner.reports.index', compact('data', 'period', 'from', 'to'));
    }

    /**
     * Template Method Pattern — template umum pembuatan laporan
     * Output detail disesuaikan berdasarkan $period
     */
    private function generateReport(string $period, string $from, string $to): array
    {
        // Step 1: Query dasar
        $query = Transaction::where('payment_status', 'Success')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->with('booking.table', 'booking.user');

        $transactions = $query->get();

        // Step 2: Hitung total
        $totalRevenue  = $transactions->sum('total_amount');
        $totalBookings = $transactions->count();
        $avgTicket     = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;

        // Step 3: Build chart data (spesifik per period)
        $chartData = match($period) {
            'weekly'  => $this->buildWeeklyChart($from, $to),
            'monthly' => $this->buildMonthlyChart($from, $to),
            default   => $this->buildDailyChart($from, $to),
        };

        // Step 4: Top tables
        $topTables = $transactions->groupBy('booking.table_id')
            ->map(fn($group) => [
                'table_number' => $group->first()->booking->table->table_number ?? 'N/A',
                'revenue'      => $group->sum('total_amount'),
                'count'        => $group->count(),
            ])
            ->sortByDesc('revenue')
            ->take(5)
            ->values();

        // Step 5: Rating stats
        $avgRating = Feedback::whereHas('booking', function ($q) use ($from, $to) {
            $q->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
        })->avg('rating') ?? 0;

        return [
            'total_revenue'  => $totalRevenue,
            'total_bookings' => $totalBookings,
            'avg_ticket'     => $avgTicket,
            'avg_rating'     => round($avgRating, 1),
            'chart_data'     => $chartData,
            'top_tables'     => $topTables,
            'transactions'   => $transactions->take(20),
        ];
    }

    private function buildDailyChart(string $from, string $to): array
    {
        $data = [];
        $start = Carbon::parse($from);
        $end   = Carbon::parse($to);

        while ($start->lte($end)) {
            $revenue = Transaction::where('payment_status', 'Success')
                ->whereDate('created_at', $start->format('Y-m-d'))
                ->sum('total_amount');

            $data[] = [
                'label'   => $start->format('d/m'),
                'revenue' => (float) $revenue,
                'bookings'=> Transaction::where('payment_status', 'Success')
                    ->whereDate('created_at', $start->format('Y-m-d'))->count(),
            ];
            $start->addDay();
        }
        return $data;
    }

    private function buildWeeklyChart(string $from, string $to): array
    {
        $data  = [];
        $start = Carbon::parse($from)->startOfWeek();
        $end   = Carbon::parse($to);

        while ($start->lte($end)) {
            $weekEnd = $start->copy()->endOfWeek();
            $revenue = Transaction::where('payment_status', 'Success')
                ->whereBetween('created_at', [$start, $weekEnd])
                ->sum('total_amount');

            $data[] = [
                'label'   => 'W' . $start->weekOfYear,
                'revenue' => (float) $revenue,
            ];
            $start->addWeek();
        }
        return $data;
    }

    private function buildMonthlyChart(string $from, string $to): array
    {
        $data  = [];
        $start = Carbon::parse($from)->startOfMonth();
        $end   = Carbon::parse($to);

        while ($start->lte($end)) {
            $revenue = Transaction::where('payment_status', 'Success')
                ->whereYear('created_at', $start->year)
                ->whereMonth('created_at', $start->month)
                ->sum('total_amount');

            $data[] = [
                'label'   => $start->format('M Y'),
                'revenue' => (float) $revenue,
            ];
            $start->addMonth();
        }
        return $data;
    }
}
