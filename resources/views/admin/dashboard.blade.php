@extends('layouts.bootstrap')

@section('content')
<h2 class="mb-4 text-white font-weight-bold">
    <i class="bi bi-speedometer2 text-success me-2"></i>Dashboard Pengelola
</h2>

<!-- Stats Grid -->
<div class="row mb-4">
    <!-- Stat Card 1 -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-cue h-100 border-0 bg-gradient" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-light-emphasis small text-uppercase">Total Reservasi</h6>
                    <h3 class="text-white font-weight-bold mb-0">{{ $stats['total_reservations'] }}</h3>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-3 fs-3">
                    <i class="bi bi-calendar-event"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stat Card 2 -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-cue h-100 border-0 bg-gradient" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-light-emphasis small text-uppercase">Menunggu Bayar</h6>
                    <h3 class="text-warning font-weight-bold mb-0">{{ $stats['pending_reservations'] }}</h3>
                </div>
                <div class="bg-warning-subtle text-warning p-3 rounded-3 fs-3">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-cue h-100 border-0 bg-gradient" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-light-emphasis small text-uppercase">Konfirmasi Aktif</h6>
                    <h3 class="text-success font-weight-bold mb-0">{{ $stats['confirmed_reservations'] }}</h3>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-3 fs-3">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-cue h-100 border-0 bg-gradient" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-light-emphasis small text-uppercase">Total Pendapatan</h6>
                    <h3 class="text-emerald font-weight-bold mb-0 text-success">Rp{{ number_format($stats['total_earnings'], 0, ',', '.') }}</h3>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-3 fs-3">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Status Overview & Reservation Manager -->
<div class="row">
    <!-- Visual Table Status Map -->
    <div class="col-lg-12 mb-4">
        <div class="card card-cue p-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-white mb-0"><i class="bi bi-grid-3x3-gap-fill text-success me-2"></i>Status Lapangan Billiard</h5>
                    <a href="{{ route('admin.tables') }}" class="btn btn-sm btn-outline-success">Ubah Status Meja</a>
                </div>
                <div class="row">
                    @foreach($tables as $table)
                        <div class="col-lg-2.4 col-md-4 col-sm-6 mb-3">
                            @php
                                $cardColor = match($table->status) {
                                    'Available' => 'border-success bg-success-subtle text-success',
                                    'Occupied' => 'border-danger bg-danger-subtle text-danger',
                                    'Booked' => 'border-warning bg-warning-subtle text-warning',
                                    'Maintenance' => 'border-secondary bg-secondary-subtle text-secondary',
                                    default => 'border-secondary',
                                };
                            @endphp
                            <div class="card h-100 text-center p-3 border-2 {{ $cardColor }}">
                                <h6 class="mb-1 fw-bold">MEJA {{ $table->table_number }}</h6>
                                <div class="small fw-semibold">{{ $table->status }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Booking List Panel -->
    <div class="col-lg-12">
        <div class="card card-cue">
            <div class="card-header bg-dark border-secondary p-3 d-flex justify-content-between align-items-center">
                <h5 class="text-white mb-0"><i class="bi bi-list-stars text-success me-2"></i>Kelola Daftar Reservasi</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead class="table-light text-uppercase fs-7">
                            <tr>
                                <th class="ps-4">ID Reservasi</th>
                                <th>Pelanggan</th>
                                <th>Meja</th>
                                <th>Waktu Sewa</th>
                                <th>Total Tagihan</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi Transisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $res)
                                <tr>
                                    <td class="ps-4 fw-bold text-success">{{ $res->id }}</td>
                                    <td>
                                        <div class="text-white fw-medium">{{ $res->user->name }}</div>
                                        <div class="text-light-emphasis small">{{ $res->user->email }} | {{ $res->user->phone ?? '-' }}</div>
                                    </td>
                                    <td><span class="badge bg-secondary">Meja {{ $res->table->table_number }}</span></td>
                                    <td>
                                        <div class="small">{{ $res->start_time->format('d M, H:i') }} - {{ $res->end_time->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="fw-semibold text-white">Rp{{ number_format($res->total_price, 0, ',', '.') }}</td>
                                    <td>{{ $res->payment_method }}</td>
                                    <td>
                                        <!-- Utilizes State Pattern's Badge style output dynamically -->
                                        <span class="badge {{ $res->getState()->getBadgeClass() }} px-2.5 py-1.5 rounded-pill">
                                            {{ $res->getState()->getStatusName() }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1.5">
                                            @if($res->status === 'Pending')
                                                <!-- Action calls transition to ConfirmedState -->
                                                <form action="{{ route('admin.reservations.status', $res->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Confirmed">
                                                    <button type="submit" class="btn btn-sm btn-success py-1 px-2" title="Konfirmasi Reservasi">
                                                        <i class="bi bi-check-circle"></i> Konfirmasi
                                                    </button>
                                                </form>
                                                
                                                <!-- Action calls transition to CancelledState -->
                                                <form action="{{ route('admin.reservations.status', $res->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Cancelled">
                                                    <button type="submit" class="btn btn-sm btn-danger py-1 px-2" title="Batalkan Reservasi" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')">
                                                        <i class="bi bi-x-circle"></i> Batalkan
                                                    </button>
                                                </form>
                                            @elseif($res->status === 'Confirmed')
                                                <!-- State Pattern: Active Confirmed can only transition to Cancelled -->
                                                <form action="{{ route('admin.reservations.status', $res->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Cancelled">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Batalkan Reservasi" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')">
                                                        <i class="bi bi-x-circle"></i> Batalkan
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" disabled>
                                                    Terminal State
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-light-emphasis">
                                        <i class="bi bi-inbox display-4 mb-3 d-block text-secondary"></i>
                                        Belum ada data reservasi masuk.
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
