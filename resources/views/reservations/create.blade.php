@extends('layouts.bootstrap')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <h2 class="mb-4 text-white font-weight-bold">
            <i class="bi bi-calendar-plus text-success me-2"></i>Buat Reservasi Baru
        </h2>

        <!-- Billiard Table Availability Panel -->
        <div class="row mb-5">
            <div class="col-12">
                <h5 class="mb-3 text-light"><i class="bi bi-grid text-success me-2"></i>Status Meja Billiard Saat Ini</h5>
            </div>
            @foreach($tables as $table)
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card card-cue h-100 {{ $table->status === 'Maintenance' ? 'opacity-75' : '' }}">
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="card-title mb-0 text-white font-weight-bold">MEJA {{ $table->table_number }}</h4>
                                    @php
                                        $statusClass = match($table->status) {
                                            'Available' => 'bg-success',
                                            'Occupied' => 'bg-danger',
                                            'Booked' => 'bg-warning text-dark',
                                            'Maintenance' => 'bg-secondary',
                                            default => 'bg-info',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-2.5 py-1.5">{{ $table->status }}</span>
                                </div>
                                <h6 class="text-light-emphasis mb-0 small">Harga Sewa:</h6>
                                <p class="h5 text-success font-weight-bold">Rp{{ number_format($table->price_per_hour, 0, ',', '.') }}<span class="text-light-emphasis small fw-normal">/jam</span></p>
                            </div>
                            <div class="mt-3">
                                @if($table->status === 'Available' || $table->status === 'Booked')
                                    <button type="button" class="btn btn-cue btn-sm w-100 btn-select-table" data-table-id="{{ $table->id }}" data-table-number="{{ $table->table_number }}">
                                        Pilih Meja ini
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" disabled>
                                        {{ $table->status === 'Maintenance' ? 'Pemeliharaan' : 'Sedang Dipakai' }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Reservation Form -->
        <div class="card card-cue p-4">
            <div class="card-body">
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Table Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="table_id" class="form-label text-light-emphasis"><i class="bi bi-grid-fill me-1"></i>Pilih Meja</label>
                            <select class="form-select bg-dark text-white border-secondary @error('table_id') is-invalid @enderror" id="table_id" name="table_id" required>
                                <option value="">-- Pilih Meja Billiard --</option>
                                @foreach($tables as $table)
                                    @if($table->status !== 'Maintenance')
                                        <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }}>
                                            Meja {{ $table->table_number }} (Rp{{ number_format($table->price_per_hour, 0, ',', '.') }}/jam) - {{ $table->status }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('table_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label text-light-emphasis"><i class="bi bi-credit-card-fill me-1"></i>Metode Pembayaran</label>
                            <select class="form-select bg-dark text-white border-secondary @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS (Instan & Mock QR)</option>
                                <option value="Transfer Bank" {{ old('payment_method') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank (Virtual Account)</option>
                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Bayar di Kasir (Cash)</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Dynamic Bank List (Shown only for Bank Transfer Strategy) -->
                    <div class="row d-none" id="bank_select_wrapper">
                        <div class="col-12 mb-3">
                            <label for="bank_name" class="form-label text-light-emphasis"><i class="bi bi-bank me-1"></i>Pilih Bank</label>
                            <select class="form-select bg-dark text-white border-secondary @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name">
                                <option value="">-- Pilih Bank Transfer --</option>
                                <option value="BCA" {{ old('bank_name') == 'BCA' ? 'selected' : '' }}>Bank Central Asia (BCA)</option>
                                <option value="Mandiri" {{ old('bank_name') == 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                                <option value="BNI" {{ old('bank_name') == 'BNI' ? 'selected' : '' }}>Bank Negara Indonesia (BNI)</option>
                            </select>
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Start Time -->
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label text-light-emphasis"><i class="bi bi-clock me-1"></i>Waktu Mulai</label>
                            <input type="datetime-local" class="form-control bg-dark text-white border-secondary @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label text-light-emphasis"><i class="bi bi-clock-fill me-1"></i>Waktu Selesai</label>
                            <input type="datetime-local" class="form-control bg-dark text-white border-secondary @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Summary Info -->
                    <div class="alert alert-dark border-secondary p-3 mt-3 shadow-inner text-light">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Estimasi Waktu Sewa:</span>
                            <strong id="summary-duration">- jam</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="h5 mb-0 font-weight-bold">Estimasi Total Biaya:</span>
                            <strong class="h5 text-success mb-0" id="summary-price">Rp0</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary px-4 py-2">Batal</a>
                        <button type="submit" class="btn btn-cue px-5 py-2">Lanjutkan Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableSelect = document.getElementById('table_id');
        const paymentSelect = document.getElementById('payment_method');
        const bankWrapper = document.getElementById('bank_select_wrapper');
        const bankName = document.getElementById('bank_name');
        
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');
        
        const summaryDuration = document.getElementById('summary-duration');
        const summaryPrice = document.getElementById('summary-price');
        
        // Quick visual selector logic
        const buttonsSelect = document.querySelectorAll('.btn-select-table');
        buttonsSelect.forEach(btn => {
            btn.addEventListener('click', function() {
                const tableId = this.getAttribute('data-table-id');
                tableSelect.value = tableId;
                
                // Trigger change event to compute prices
                tableSelect.dispatchEvent(new Event('change'));
                
                // Highlight current card visually (optional)
                buttonsSelect.forEach(b => {
                    b.classList.remove('btn-success');
                    b.classList.add('btn-cue');
                    b.innerText = "Pilih Meja ini";
                });
                this.classList.remove('btn-cue');
                this.classList.add('btn-success');
                this.innerText = "Meja Terpilih";

                // Scroll to form smoothly
                tableSelect.scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Toggle Bank select field when Transfer Bank payment option selected
        paymentSelect.addEventListener('change', function() {
            if (this.value === 'Transfer Bank') {
                bankWrapper.classList.remove('d-none');
                bankName.setAttribute('required', 'required');
            } else {
                bankWrapper.classList.add('d-none');
                bankName.removeAttribute('required');
                bankName.value = '';
            }
        });

        // Automatic price calculator
        function calculateEstimatedPrice() {
            const startVal = startTime.value;
            const endVal = endTime.value;
            const selectedOpt = tableSelect.options[tableSelect.selectedIndex];
            
            if (!startVal || !endVal || !tableSelect.value) {
                summaryDuration.innerText = "- jam";
                summaryPrice.innerText = "Rp0";
                return;
            }

            const startObj = new Date(startVal);
            const endObj = new Date(endVal);
            
            const diffMs = endObj - startObj;
            if (diffMs <= 0) {
                summaryDuration.innerText = "Durasi tidak valid";
                summaryPrice.innerText = "Rp0";
                return;
            }

            const hours = diffMs / (1000 * 60 * 60);
            
            // Extract price per hour from option text using regex or store data attribute
            let rate = 50000; // fallback
            const optText = selectedOpt.text;
            const priceMatch = optText.match(/Rp([\d.]+)/);
            if (priceMatch) {
                rate = parseInt(priceMatch[1].replace(/\./g, ''));
            }

            const totalPrice = rate * hours;
            
            summaryDuration.innerText = hours.toFixed(1) + " jam";
            summaryPrice.innerText = "Rp" + totalPrice.toLocaleString('id-ID');
        }

        tableSelect.addEventListener('change', calculateEstimatedPrice);
        startTime.addEventListener('change', calculateEstimatedPrice);
        endTime.addEventListener('change', calculateEstimatedPrice);
    });
</script>
@endsection
