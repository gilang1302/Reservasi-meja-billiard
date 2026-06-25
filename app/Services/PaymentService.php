<?php

namespace App\Services;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Strategies\PaymentStrategy;
use App\Strategies\QrisPaymentStrategy;
use App\Strategies\BankTransferPaymentStrategy;
use App\Strategies\CashPaymentStrategy;
use Illuminate\Support\Str;

class PaymentService
{
    protected PaymentRepositoryInterface $paymentRepo;
    protected ReservationRepositoryInterface $reservationRepo;
    protected ReservationService $reservationService;

    public function __construct(
        PaymentRepositoryInterface $paymentRepo,
        ReservationRepositoryInterface $reservationRepo,
        ReservationService $reservationService
    ) {
        $this->paymentRepo = $paymentRepo;
        $this->reservationRepo = $reservationRepo;
        $this->reservationService = $reservationService;
    }

    /**
     * Resolve the active payment strategy.
     *
     * @param string $method
     * @return PaymentStrategy
     * @throws \Exception
     */
    protected function resolveStrategy(string $method): PaymentStrategy
    {
        return match ($method) {
            'QRIS' => new QrisPaymentStrategy(),
            'Transfer Bank' => new BankTransferPaymentStrategy(),
            'Cash' => new CashPaymentStrategy(),
            default => throw new \Exception("Metode pembayaran tidak valid."),
        };
    }

    /**
     * Process payment for a reservation.
     *
     * @param string $reservationId
     * @param string $method
     * @param array $details
     * @return Payment
     * @throws \Exception
     */
    public function initiatePayment(string $reservationId, string $method, array $details = []): Payment
    {
        $reservation = $this->reservationRepo->find($reservationId);
        if (!$reservation) {
            throw new \Exception("Reservasi tidak ditemukan.");
        }

        // 1. Resolve strategy
        $strategy = $this->resolveStrategy($method);

        // 2. Execute strategy
        $details['reservation_id'] = $reservationId;
        $paymentResult = $strategy->processPayment((float)$reservation->total_price, $details);

        // 3. Generate payment ID
        $datePrefix = now()->format('Ymd');
        $randomSuffix = strtoupper(Str::random(4));
        $paymentId = "PMT-{$datePrefix}-{$randomSuffix}";

        // 4. Save Payment record
        return $this->paymentRepo->create([
            'id' => $paymentId,
            'reservation_id' => $reservationId,
            'amount' => $reservation->total_price,
            'payment_method' => $method,
            'status' => $paymentResult['status'], // Initial strategy status (Pending)
            'payment_date' => now(),
            'transaction_details' => $paymentResult['transaction_details'],
        ]);
    }

    /**
     * Complete and confirm payment (triggered on successful checkout or admin approval).
     *
     * @param string $paymentId
     * @throws \Exception
     */
    public function completePayment(string $paymentId): void
    {
        $payment = $this->paymentRepo->find($paymentId);
        if (!$payment) {
            throw new \Exception("Pembayaran tidak ditemukan.");
        }

        if ($payment->status === 'Success') {
            return; // Already paid
        }

        // 1. Update Payment status to Success
        $this->paymentRepo->updateStatus($paymentId, 'Success');

        // 2. Confirm the reservation status via State pattern (Pending -> Confirmed)
        $this->reservationService->updateReservationStatus($payment->reservation_id, 'Confirmed');
    }
}
