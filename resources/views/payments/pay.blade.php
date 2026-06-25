@extends('layouts.bootstrap')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <h2 class="mb-4 text-white text-center font-weight-bold">
            <i class="bi bi-wallet2 text-success me-2"></i>Selesaikan Pembayaran Anda
        </h2>

        <div class="card card-cue p-4">
            <div class="card-body">
                <div class="text-center mb-4 border-bottom border-secondary pb-3">
                    <span class="text-light-emphasis small d-block">TOTAL PEMBAYARAN</span>
                    <h1 class="text-success font-weight-bold my-2">Rp{{ number_format($payment->amount, 0, ',', '.') }}</h1>
                    <span class="badge bg-secondary">ID Transaksi: {{ $payment->id }}</span>
                </div>

                <!-- Strategy Rendering 1: QRIS -->
                @if($payment->payment_method === 'QRIS')
                    <div class="text-center">
                        <h5 class="text-white mb-1">{{ $payment->transaction_details['merchant_name'] ?? 'CueMaster' }}</h5>
                        <p class="text-light-emphasis small mb-3">NMID: {{ $payment->transaction_details['qris_id'] ?? '' }}</p>
                        
                        <!-- Show QR code from strategy url -->
                        <div class="bg-white p-3 rounded-4 d-inline-block shadow mb-4">
                            <img src="{{ $payment->transaction_details['qr_url'] }}" alt="QRIS QR Code" class="img-fluid" style="width: 250px; height: 250px;">
                        </div>
                        
                        <div class="alert alert-dark text-light border-secondary small text-start">
                            <ol class="mb-0 ps-3">
                                <li>Buka aplikasi mobile banking atau e-wallet (GoPay, OVO, Dana, LinkAja, BCA, dll).</li>
                                <li>Scan kode QRIS di atas.</li>
                                <li>Konfirmasi nominal pembayaran sesuai total di atas.</li>
                                <li>Masukkan PIN e-wallet Anda.</li>
                            </ol>
                        </div>
                        
                        <p class="text-danger small"><i class="bi bi-alarm me-1"></i>Batas bayar: {{ date('H:i:s d M Y', strtotime($payment->transaction_details['expires_at'])) }}</p>
                    </div>

                <!-- Strategy Rendering 2: Bank Transfer -->
                @elseif($payment->payment_method === 'Transfer Bank')
                    <div>
                        <h5 class="text-light-emphasis text-center mb-3">TRANSFER VIRTUAL ACCOUNT</h5>
                        
                        <div class="bg-dark p-3 rounded-3 border border-secondary text-center mb-4">
                            <h6 class="text-light-emphasis mb-1">Nama Bank:</h6>
                            <h4 class="text-white font-weight-bold mb-3">{{ $payment->transaction_details['bank_name'] }}</h4>
                            
                            <h6 class="text-light-emphasis mb-1">Nomor Virtual Account:</h6>
                            <h2 class="text-success font-weight-bold tracking-widest mb-2" id="vaNumber">{{ $payment->transaction_details['virtual_account'] }}</h2>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1" onclick="copyVA()">
                                <i class="bi bi-copy me-1"></i> Salin VA
                            </button>
                        </div>

                        <div class="small mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-light-emphasis">Nama Rekening:</span>
                                <span class="text-white">{{ $payment->transaction_details['account_name'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-light-emphasis">Batas Transfer:</span>
                                <span class="text-danger fw-semibold">{{ date('H:i:s d M Y', strtotime($payment->transaction_details['expires_at'])) }}</span>
                            </div>
                        </div>

                        <div class="alert alert-dark text-light border-secondary small">
                            <strong>Cara Pembayaran:</strong>
                            <ol class="mb-0 ps-3 mt-1">
                                <li>Pilih menu transfer virtual account pada ATM/m-Banking.</li>
                                <li>Masukkan nomor VA di atas.</li>
                                <li>Konfirmasi tagihan & selesaikan transaksi.</li>
                            </ol>
                        </div>
                    </div>

                <!-- Strategy Rendering 3: Cash -->
                @elseif($payment->payment_method === 'Cash')
                    <div class="text-center py-3">
                        <i class="bi bi-cash-coin display-2 text-success mb-3"></i>
                        <h5 class="text-white mb-3">Bayar Tunai di Kasir</h5>
                        
                        <div class="alert alert-dark border-secondary text-light text-start small mb-4">
                            <p class="mb-2"><strong>Instruksi Pembayaran:</strong></p>
                            <p class="mb-0">{{ $payment->transaction_details['cashier_instructions'] }}</p>
                        </div>

                        <div class="small">
                            <div class="d-flex justify-content-between">
                                <span class="text-light-emphasis">Lokasi Pembayaran:</span>
                                <span class="text-white">{{ $payment->transaction_details['payment_location'] }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment Action Button -->
                <div class="mt-4 border-top border-secondary pt-4 text-center">
                    @if($payment->payment_method === 'Cash')
                        <a href="{{ route('reservations.index') }}" class="btn btn-cue w-100 py-2.5">
                            <i class="bi bi-clock-history me-1"></i> Lihat Riwayat Reservasi
                        </a>
                        <p class="text-light-emphasis small mt-2">Reservasi Anda berstatus PENDING hingga pembayaran tunai diterima kasir.</p>
                    @else
                        <!-- Customer simulated pay trigger -->
                        <form action="{{ route('payments.confirm', $payment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-cue w-100 py-2.5">
                                <i class="bi bi-check-circle-fill me-1"></i> Saya Sudah Bayar (Simulasi)
                            </button>
                        </form>
                        <a href="{{ route('reservations.index') }}" class="btn btn-link text-light-emphasis text-decoration-none mt-2 d-inline-block small">
                            Bayar nanti
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyVA() {
        const vaText = document.getElementById('vaNumber').innerText;
        navigator.clipboard.writeText(vaText).then(() => {
            alert('Nomor Virtual Account berhasil disalin!');
        });
    }
</script>
@endsection
