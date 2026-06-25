@extends('layouts.bootstrap')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white mb-0 font-weight-bold">
                <i class="bi bi-clock-history text-success me-2"></i>Riwayat Reservasi Anda
            </h2>
            <a href="{{ route('reservations.create') }}" class="btn btn-cue px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i> Reservasi Baru
            </a>
        </div>

        <div class="card card-cue">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead class="table-light text-uppercase fs-7">
                            <tr>
                                <th class="ps-4">ID Reservasi</th>
                                <th>Meja</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Total Biaya</th>
                                <th>Metode Bayar</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $res)
                                <tr>
                                    <td class="ps-4 fw-bold text-success">{{ $res->id }}</td>
                                    <td>
                                        <span class="badge bg-secondary">Meja {{ $res->table->table_number }}</span>
                                    </td>
                                    <td>{{ $res->start_time->format('d M Y, H:i') }} WIB</td>
                                    <td>{{ $res->end_time->format('d M Y, H:i') }} WIB</td>
                                    <td class="fw-semibold text-white">Rp{{ number_format($res->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $methodIcon = match($res->payment_method) {
                                                'QRIS' => 'bi-qr-code',
                                                'Transfer Bank' => 'bi-bank',
                                                'Cash' => 'bi-cash-coin',
                                                default => 'bi-credit-card',
                                            };
                                        @endphp
                                        <i class="bi {{ $methodIcon }} text-light-emphasis me-1"></i>{{ $res->payment_method }}
                                    </td>
                                    <td>
                                        <!-- Utilizing the State Pattern's Badge CSS mapping dynamically -->
                                        <span class="badge {{ $res->getState()->getBadgeClass() }} px-2.5 py-1.5 rounded-pill">
                                            {{ $res->getState()->getStatusName() }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1.5">
                                            @if($res->status === 'Pending' && $res->payment)
                                                <a href="{{ route('payments.pay', $res->payment->id) }}" class="btn btn-sm btn-cue py-1 px-2.5">
                                                    <i class="bi bi-credit-card-2-back me-1"></i> Bayar
                                                </a>
                                            @endif

                                            @if($res->status === 'Pending')
                                                <!-- Cancellation triggers status transition inside PendingState -->
                                                <form action="{{ route('reservations.cancel', $res->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2.5">
                                                        <i class="bi bi-x-circle me-1"></i> Batalkan
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5" disabled>
                                                    Tidak ada aksi
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-light-emphasis">
                                        <i class="bi bi-calendar-x display-4 mb-3 d-block text-secondary"></i>
                                        Belum ada riwayat reservasi.
                                        <a href="{{ route('reservations.create') }}" class="d-block mt-2 text-success text-decoration-none">
                                            Buat Reservasi Pertama Anda &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
