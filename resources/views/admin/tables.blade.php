@extends('layouts.bootstrap')

@section('content')
<h2 class="mb-4 text-white font-weight-bold">
    <i class="bi bi-grid-3x3-gap text-success me-2"></i>Kelola Meja Billiard
</h2>

<div class="row">
    @foreach($tables as $table)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card card-cue h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title text-white font-weight-bold mb-0">MEJA {{ $table->table_number }}</h4>
                            @php
                                $badgeColor = match($table->status) {
                                    'Available' => 'bg-success',
                                    'Occupied' => 'bg-danger',
                                    'Booked' => 'bg-warning text-dark',
                                    'Maintenance' => 'bg-secondary',
                                    default => 'bg-info',
                                };
                            @endphp
                            <span class="badge {{ $badgeColor }} px-2.5 py-1.5">{{ $table->status }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-light-emphasis small mb-0">Harga Sewa:</h6>
                            <h5 class="text-success font-weight-bold">Rp{{ number_format($table->price_per_hour, 0, ',', '.') }} <span class="text-light-emphasis small fw-normal">/jam</span></h5>
                            <span class="text-light-emphasis small">ID: {{ $table->id }}</span>
                        </div>
                    </div>

                    <!-- Change Status Form -->
                    <div class="border-top border-secondary pt-3 mt-3">
                        <form action="{{ route('admin.tables.status', $table->id) }}" method="POST">
                            @csrf
                            <label class="form-label text-light-emphasis small mb-2">Ubah Status Operasional:</label>
                            <div class="input-group input-group-sm">
                                <select class="form-select bg-dark text-white border-secondary" name="status" required>
                                    <option value="Available" {{ $table->status === 'Available' ? 'selected' : '' }}>Available (Tersedia)</option>
                                    <option value="Occupied" {{ $table->status === 'Occupied' ? 'selected' : '' }}>Occupied (Digunakan)</option>
                                    <option value="Booked" {{ $table->status === 'Booked' ? 'selected' : '' }}>Booked (Dipesan)</option>
                                    <option value="Maintenance" {{ $table->status === 'Maintenance' ? 'selected' : '' }}>Maintenance (Perbaikan)</option>
                                </select>
                                <button type="submit" class="btn btn-cue btn-sm">Update</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
