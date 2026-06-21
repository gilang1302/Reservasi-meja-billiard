<?php

namespace App\Reports;

use App\Models\Booking;
use App\Models\Order;
use Carbon\Carbon;

abstract class ReportTemplate
{
    /**
     * Template Method - Mendefinisikan kerangka pembuatan laporan
     */
    public final function generate(Carbon $startDate, Carbon $endDate): array
    {
        $bookings = $this->fetchBookings($startDate, $endDate);
        $orders = $this->fetchOrders($startDate, $endDate);
        
        $summary = $this->calculateSummary($bookings, $orders);
        $groupedData = $this->groupData($bookings, $orders, $startDate, $endDate);
        
        return [
            'summary' => $summary,
            'chart_data' => $groupedData,
            'title' => $this->getReportTitle(),
            'period' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
        ];
    }

    protected function fetchBookings(Carbon $startDate, Carbon $endDate)
    {
        return Booking::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->whereIn('status', ['Completed', 'Confirmed', 'Active'])
            ->with(['user', 'table'])
            ->get();
    }

    protected function fetchOrders(Carbon $startDate, Carbon $endDate)
    {
        return Order::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->where('status', '!=', 'Cancelled')
            ->with('details')
            ->get();
    }

    protected function calculateSummary($bookings, $orders): array
    {
        $bookingRevenue = $bookings->sum('total_price');
        $orderRevenue = $orders->sum('total_price');
        $totalRevenue = $bookingRevenue + $orderRevenue;
        
        return [
            'booking_revenue' => $bookingRevenue,
            'order_revenue' => $orderRevenue,
            'total_revenue' => $totalRevenue,
            'bookings_count' => $bookings->count(),
            'orders_count' => $orders->count(),
        ];
    }

    // Abstract methods yang harus diimplementasikan oleh subclass
    abstract protected function groupData($bookings, $orders, Carbon $startDate, Carbon $endDate): array;
    abstract protected function getReportTitle(): string;
}
