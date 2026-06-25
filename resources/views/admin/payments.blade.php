@extends('layouts.bootstrap')

@section('content')
<h2 class="mb-4 text-white font-weight-bold">
    <i class="bi bi-credit-card text-success me-2"></i>Kelola Pembayaran Reservasi
</h2>

<div class="card card-cue">
    <div class="card-header bg-dark border-secondary p-3">
        <h5 class="text-white mb-0"><i class="bi bi-list-check text-success me-2"></i>Riwayat Transaksi Pembayaran</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead class="table-light text-uppercase fs-7">
                    <tr>
                        <th class="ps-4">ID Pembayaran</th>
                        <th>ID Reservasi</th>
                        <th>Pelanggan</th>
                        <th>Jumlah Bayar</th>
                        <th>Metode</th>
                        <th>Tanggal Transaksi</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $pmt)
                        <tr>
                            <td class="ps-4 fw-bold text-success">{{ $pmt->id }}</td>
                            <td class="fw-medium text-white">{{ $pmt->reservation_id }}</td>
                            <td>
                                @if($pmt->reservation && $pmt->reservation->user)
                                    <div>{{ $pmt->reservation->user->name }}</div>
                                    <div class="text-light-emphasis small">{{ $pmt->reservation->user->phone ?? '-' }}</div>
                                @else
                                    <span class="text-light-emphasis">-</span>
                                @endif
                            </td>
                            <td class="fw-semibold text-white">Rp{{ number_format($pmt->amount, 0, ',', '.') }}</td>
                            <td>{{ $pmt->payment_method }}</td>
                            <td>{{ $pmt->payment_date->format('d M Y, H:i') }} WIB</td>
                            <td>
                                @php
                                    $badgeColor = match($pmt->status) {
                                        'Success' => 'bg-success',
                                        'Pending' => 'bg-warning text-dark',
                                        'Failed' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                    $statusName = match($pmt->status) {
                                        'Success' => 'Lunas',
                                        'Pending' => 'Belum Bayar',
                                        'Failed' => 'Gagal',
                                        default => $pmt->status,
                                    };
                                @endphp
                                <span class="badge {{ $badgeColor }} px-2.5 py-1.5 rounded-pill">
                                    {{ $statusName }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                @if($pmt->status === 'Pending' && $pmt->payment_method === 'Cash')
                                    <!-- Admin/Cashier manual cash confirmation -->
                                    <form action="{{ route('admin.payments.approve', $pmt->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa uang cash telah diterima di kasir?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-cue py-1 px-2.5">
                                            <i class="bi bi-cash-coin me-1"></i> Terima Uang Cash
                                        </button>
                                    </form>
                                @elseif($pmt->status === 'Pending' && $pmt->payment_method !== 'Cash')
                                    <!-- For simulator support, allow admin to confirm other payments too -->
                                    <form action="{{ route('admin.payments.approve', $pmt->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning py-1 px-2.5">
                                            <i class="bi bi-check-circle me-1"></i> Paksa Lunas
                                        </button>
                                    </form>
                                @else
                                    <span class="text-light-emphasis small"><i class="bi bi-shield-fill-check text-success me-1"></i>Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-light-emphasis">
                                <i class="bi bi-credit-card-2-front display-4 mb-3 d-block text-secondary"></i>
                                Belum ada data pembayaran masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
