<?php

namespace App\Reports;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DailyReport extends ReportTemplate
{
    protected function getReportTitle(): string
    {
        return 'Laporan Pendapatan Harian';
    }

    protected function groupData($bookings, $orders, Carbon $startDate, Carbon $endDate): array
    {
        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $bookingData = [];
        $orderData = [];
        $totalData = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $date->format('d M');
            $labels[] = $displayDate;

            $dayBookings = $bookings->filter(fn($b) => $b->created_at->format('Y-m-d') === $formattedDate);
            $dayOrders = $orders->filter(fn($o) => $o->created_at->format('Y-m-d') === $formattedDate);

            $bRev = (float) $dayBookings->sum('total_price');
            $oRev = (float) $dayOrders->sum('total_price');

            $bookingData[] = $bRev;
            $orderData[] = $oRev;
            $totalData[] = $bRev + $oRev;
        }

        return [
            'labels' => $labels,
            'booking_revenue' => $bookingData,
            'order_revenue' => $orderData,
            'total_revenue' => $totalData,
        ];
    }
}
