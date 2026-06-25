<?php

namespace App\Repositories\Implementations;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function all(): Collection
    {
        return Payment::with('reservation')->orderBy('created_at', 'desc')->get();
    }

    public function find(string $id): ?Payment
    {
        return Payment::with('reservation')->find($id);
    }

    public function findByReservationId(string $reservationId): ?Payment
    {
        return Payment::where('reservation_id', $reservationId)->first();
    }

    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function updateStatus(string $id, string $status): bool
    {
        $payment = Payment::find($id);
        if ($payment) {
            return $payment->update(['status' => $status]);
        }
        return false;
    }
}
