<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;
    protected PaymentRepositoryInterface $paymentRepo;

    public function __construct(
        PaymentService $paymentService,
        PaymentRepositoryInterface $paymentRepo
    ) {
        $this->paymentService = $paymentService;
        $this->paymentRepo = $paymentRepo;
    }

    /**
     * Show checkout screen showing payment details.
     */
    public function pay(string $id)
    {
        $payment = $this->paymentRepo->find($id);
        if (!$payment) {
            abort(404, 'Pembayaran tidak ditemukan.');
        }

        return view('payments.pay', compact('payment'));
    }

    /**
     * Customer triggers simulation of payment checkout success.
     */
    public function confirm(string $id)
    {
        try {
            // Update payment to Success and trigger state change (Pending -> Confirmed)
            $this->paymentService->completePayment($id);

            return redirect()->route('reservations.index')
                ->with('success', 'Pembayaran berhasil dikonfirmasi! Status reservasi Anda sekarang AKTIF/Confirmed.');
                
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
