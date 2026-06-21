<?php

namespace App\Reports;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class WeeklyReport extends ReportTemplate
{
    protected function getReportTitle(): string
    {
        return 'Laporan Pendapatan Mingguan';
    }

    protected function groupData($bookings, $orders, Carbon $startDate, Carbon $endDate): array
    {
        // Group by week of year
        $labels = [];
        $bookingData = [];
        $orderData = [];
        $totalData = [];

        $current = $startDate->copy()->startOfWeek();
        while ($current->lte($endDate)) {
            $weekStart = $current->copy();
            $weekEnd = $current->copy()->endOfWeek();
            $labels[] = 'Min ' . $weekStart->format('W') . ' (' . $weekStart->format('d M') . ')';

            $weekBookings = $bookings->filter(fn($b) => $b->created_at->between($weekStart, $weekEnd));
            $weekOrders = $orders->filter(fn($o) => $o->created_at->between($weekStart, $weekEnd));

            $bRev = (float) $weekBookings->sum('total_price');
            $oRev = (float) $weekOrders->sum('total_price');

            $bookingData[] = $bRev;
            $orderData[] = $oRev;
            $totalData[] = $bRev + $oRev;

            $current->addWeek();
        }

        return [
            'labels' => $labels,
            'booking_revenue' => $bookingData,
            'order_revenue' => $orderData,
            'total_revenue' => $totalData,
        ];
    }
}
