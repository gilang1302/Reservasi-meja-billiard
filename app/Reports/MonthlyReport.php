<?php

namespace App\Reports;

use Carbon\Carbon;

class MonthlyReport extends ReportTemplate
{
    protected function getReportTitle(): string
    {
        return 'Laporan Pendapatan Bulanan';
    }

    protected function groupData($bookings, $orders, Carbon $startDate, Carbon $endDate): array
    {
        $labels = [];
        $bookingData = [];
        $orderData = [];
        $totalData = [];

        $current = $startDate->copy()->startOfMonth();
        while ($current->lte($endDate)) {
            $monthLabel = $current->format('M Y');
            $labels[] = $monthLabel;

            $monthBookings = $bookings->filter(fn($b) => $b->created_at->format('Y-m') === $current->format('Y-m'));
            $monthOrders = $orders->filter(fn($o) => $o->created_at->format('Y-m') === $current->format('Y-m'));

            $bRev = (float) $monthBookings->sum('total_price');
            $oRev = (float) $monthOrders->sum('total_price');

            $bookingData[] = $bRev;
            $orderData[] = $oRev;
            $totalData[] = $bRev + $oRev;

            $current->addMonth();
        }

        return [
            'labels' => $labels,
            'booking_revenue' => $bookingData,
            'order_revenue' => $orderData,
            'total_revenue' => $totalData,
        ];
    }
}
