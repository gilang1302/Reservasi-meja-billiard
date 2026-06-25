<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Repositories\Interfaces\TableRepositoryInterface;
use App\Services\ReservationService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    protected ReservationService $reservationService;
    protected TableRepositoryInterface $tableRepo;
    protected PaymentService $paymentService;

    public function __construct(
        ReservationService $reservationService,
        TableRepositoryInterface $tableRepo,
        PaymentService $paymentService
    ) {
        $this->reservationService = $reservationService;
        $this->tableRepo = $tableRepo;
        $this->paymentService = $paymentService;
    }

    /**
     * Display customer's own booking history.
     */
    public function index()
    {
        $reservations = $this->reservationService->getReservationsByUserId(Auth::id());
        
        return view('reservations.history', compact('reservations'));
    }

    /**
     * Show booking creation page.
     */
    public function create()
    {
        $tables = $this->tableRepo->all();
        
        return view('reservations.create', compact('tables'));
    }

    /**
     * Store new reservation and initialize payment strategy.
     */
    public function store(StoreReservationRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();

            // 1. Create reservation via Service Layer (runs overlaps and Factory Method)
            $reservation = $this->reservationService->createReservation($data);

            // 2. Initialize payment using the strategy resolved in PaymentService
            $details = [];
            if ($request->payment_method === 'Transfer Bank') {
                $details['bank'] = $request->bank_name;
                $details['phone'] = Auth::user()->phone ?? '081234567890';
            } elseif ($request->payment_method === 'QRIS') {
                $details['phone'] = Auth::user()->phone ?? '081234567890';
            }

            $payment = $this->paymentService->initiatePayment($reservation->id, $request->payment_method, $details);

            return redirect()->route('payments.pay', $payment->id)
                ->with('success', 'Reservasi berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display schedule calendar page.
     */
    public function calendar()
    {
        return view('reservations.calendar');
    }

    /**
     * Return calendar events as JSON for FullCalendar.
     */
    public function calendarData()
    {
        $events = $this->reservationService->getCalendarEvents();
        return response()->json($events);
    }

    /**
     * Customer cancels their own pending reservation.
     */
    public function cancel($id)
    {
        try {
            $reservation = $this->reservationService->getReservationById($id);
            
            if (!$reservation) {
                return back()->with('error', 'Reservasi tidak ditemukan.');
            }

            if ($reservation->user_id !== Auth::id()) {
                return back()->with('error', 'Anda tidak berwenang membatalkan reservasi ini.');
            }

            // Perform cancellation using State Pattern
            $this->reservationService->updateReservationStatus($id, 'Cancelled');

            return back()->with('success', 'Reservasi berhasil dibatalkan.');
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
