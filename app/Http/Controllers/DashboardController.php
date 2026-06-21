<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BilliardTable;
use App\Models\Feedback;
use App\Models\Inventory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Redirect ke dashboard berdasarkan role user
     */
    public function index()
    {
        $user = auth()->user();

        return match($user->role) {
            'owner'      => $this->ownerDashboard(),
            'kasir'      => $this->kasirDashboard(),
            default      => $this->pelangganDashboard(),
        };
    }

    /**
     * Dashboard Owner — overview bisnis, revenue, analytics
     */
    private function ownerDashboard()
    {
        $today = Carbon::today();

        $stats = [
            'total_revenue_today'   => Transaction::whereDate('created_at', $today)
                ->where('payment_status', 'Success')->sum('total_amount'),
            'total_bookings_today'  => Booking::whereDate('created_at', $today)->count(),
            'active_tables'         => BilliardTable::where('status', 'Occupied')->count(),
            'total_customers'       => User::where('role', 'pelanggan')->count(),
            'total_revenue_month'   => Transaction::whereMonth('created_at', now()->month)
                ->where('payment_status', 'Success')->sum('total_amount'),
            'avg_rating'            => Feedback::avg('rating') ?? 0,
            'pending_orders'        => Order::where('status', 'Pending')->count(),
        ];

        // Data untuk grafik 7 hari terakhir
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenueChart[] = [
                'date'    => $date->format('d/m'),
                'revenue' => Transaction::whereDate('created_at', $date)
                    ->where('payment_status', 'Success')->sum('total_amount'),
                'bookings' => Booking::whereDate('created_at', $date)->count(),
            ];
        }

        // Heatmap data — meja paling sering digunakan
        $heatmapData = BilliardTable::withCount(['bookings as usage_count' => function ($q) {
            $q->where('status', 'Completed');
        }])->get();

        $recentFeedbacks = Feedback::with(['user', 'booking.table'])->latest()->take(5)->get();

        return view('owner.dashboard', compact('stats', 'revenueChart', 'heatmapData', 'recentFeedbacks'));
    }

    /**
     * Dashboard Kasir — meja aktif, transaksi, order F&B
     */
    private function kasirDashboard()
    {
        $tables  = BilliardTable::with('activeBooking.user')->orderBy('table_number')->get();
        $pendingTransactions = Transaction::where('payment_status', 'Pending')
            ->with('booking.user', 'booking.table')->latest()->take(10)->get();
        $pendingOrders = Order::where('status', 'Pending')
            ->with('booking.table', 'user', 'details')->latest()->get();
        $activeBookings = Booking::where('status', 'Active')
            ->with('user', 'table')->get();

        return view('kasir.dashboard', compact('tables', 'pendingTransactions', 'pendingOrders', 'activeBookings'));
    }

    /**
     * Dashboard Pelanggan — riwayat booking, poin, notifikasi
     */
    private function pelangganDashboard()
    {
        $user = auth()->user();

        $bookings    = $user->bookings()->with('table', 'transaction', 'feedback')
            ->orderBy('created_at', 'desc')->take(5)->get();
        $activeBooking = $user->bookings()->where('status', 'Active')->with('table', 'order.details')->first();
        $notifications = $user->notifications()->where('is_read', false)->latest()->take(5)->get();
        $totalSpent    = $user->bookings()->whereHas('transaction', fn($q) => $q->where('payment_status', 'Success'))
            ->join('transaction', 'booking.id', '=', 'transaction.booking_id')
            ->sum('transaction.total_amount');

        // Leaderboard position
        $leaderboard = User::where('role', 'pelanggan')
            ->orderBy('member_poin', 'desc')
            ->pluck('id')->toArray();
        $myRank = array_search($user->id, $leaderboard) + 1;

        return view('customer.dashboard', compact('bookings', 'activeBooking', 'notifications', 'totalSpent', 'myRank'));
    }
}
