<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\TableRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\ReservationLogRepositoryInterface;
use App\Services\ReservationService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminController extends Controller implements HasMiddleware
{
    protected ReservationService $reservationService;
    protected PaymentService $paymentService;
    protected TableRepositoryInterface $tableRepo;
    protected PaymentRepositoryInterface $paymentRepo;
    protected ReservationLogRepositoryInterface $logRepo;

    public function __construct(
        ReservationService $reservationService,
        PaymentService $paymentService,
        TableRepositoryInterface $tableRepo,
        PaymentRepositoryInterface $paymentRepo,
        ReservationLogRepositoryInterface $logRepo
    ) {
        $this->reservationService = $reservationService;
        $this->paymentService = $paymentService;
        $this->tableRepo = $tableRepo;
        $this->paymentRepo = $paymentRepo;
        $this->logRepo = $logRepo;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function (Request $request, $next) {
                if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'owner', 'kasir'])) {
                    abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
                }
                return $next($request);
            }),
        ];
    }

    /**
     * Admin Dashboard Overview.
     */
    public function dashboard()
    {
        $reservations = $this->reservationService->getAllReservations();
        $tables = $this->tableRepo->all();
        
        // Calculate statistics
        $stats = [
            'total_reservations' => $reservations->count(),
            'pending_reservations' => $reservations->where('status', 'Pending')->count(),
            'confirmed_reservations' => $reservations->where('status', 'Confirmed')->count(),
            'total_earnings' => $this->paymentRepo->all()->where('status', 'Success')->sum('amount'),
            'occupied_tables' => $tables->where('status', 'Occupied')->count(),
            'available_tables' => $tables->where('status', 'Available')->count(),
        ];

        return view('admin.dashboard', compact('reservations', 'tables', 'stats'));
    }

    /**
     * Update reservation status using State pattern.
     */
    public function updateReservationStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:Confirmed,Cancelled'
        ]);

        try {
            $this->reservationService->updateReservationStatus($id, $request->status);
            return back()->with('success', "Status reservasi berhasil diperbarui menjadi {$request->status}.");
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * View and manage payments.
     */
    public function payments()
    {
        $payments = $this->paymentRepo->all();
        return view('admin.payments', compact('payments'));
    }

    /**
     * Confirm Cash payment manually.
     */
    public function approvePayment(string $paymentId)
    {
        try {
            $this->paymentService->completePayment($paymentId);
            return back()->with('success', 'Pembayaran tunai berhasil dikonfirmasi! Status reservasi diperbarui.');
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * View system event notifications.
     */
    public function notifications()
    {
        $notifications = $this->logRepo->getLatestLogs(100);
        return view('admin.notifications', compact('notifications'));
    }

    /**
     * Manage Billiard Tables.
     */
    public function tables()
    {
        $tables = $this->tableRepo->all();
        return view('admin.tables', compact('tables'));
    }

    /**
     * Update Billiard Table status manually.
     */
    public function updateTableStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:Available,Occupied,Booked,Maintenance'
        ]);

        $success = $this->tableRepo->updateStatus($id, $request->status);

        if ($success) {
            return back()->with('success', 'Status meja billiard berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal memperbarui status meja.');
    }
}
