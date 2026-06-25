@extends('layouts.bootstrap')

@section('content')
<h2 class="mb-4 text-white font-weight-bold">
    <i class="bi bi-bell text-success me-2"></i>Notifikasi & Log Aktivitas
</h2>

<div class="card card-cue">
    <div class="card-header bg-dark border-secondary p-3 d-flex justify-content-between align-items-center">
        <h5 class="text-white mb-0"><i class="bi bi-bell-fill text-success me-2"></i>Notifikasi Sistem Terkini</h5>
        <span class="badge bg-secondary">Total Log: {{ $notifications->count() }}</span>
    </div>
    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            @forelse($notifications as $notif)
                <li class="list-group-item bg-dark text-white border-secondary p-4">
                    <div class="d-flex w-100 justify-content-between align-items-start mb-2">
                        <h6 class="mb-0 text-success fw-bold">
                            @php
                                $actionIcon = match($notif->action) {
                                    'created' => 'bi-plus-circle-fill text-success',
                                    'status_changed' => 'bi-arrow-left-right text-warning',
                                    'deleted' => 'bi-trash-fill text-danger',
                                    default => 'bi-info-circle-fill text-info',
                                };
                            @endphp
                            <i class="bi {{ $actionIcon }} me-2"></i>
                            Reservasi {{ $notif->reservation_id }}
                        </h6>
                        <small class="text-light-emphasis">
                            <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                            ({{ $notif->created_at->format('d M Y, H:i:s') }} WIB)
                        </small>
                    </div>
                    
                    <p class="mb-2 text-light-emphasis">{{ $notif->notes }}</p>
                    
                    <div class="d-flex gap-4 small mt-2 pt-2 border-top border-secondary text-secondary">
                        <div>
                            <strong>Tindakan:</strong> 
                            <span class="text-capitalize text-white">{{ $notif->action }}</span>
                        </div>
                        @if($notif->old_status)
                            <div>
                                <strong>Status Lama:</strong> 
                                <span class="badge bg-secondary">{{ $notif->old_status }}</span>
                            </div>
                        @endif
                        <div>
                            <strong>Status Baru:</strong> 
                            <span class="badge bg-success bg-opacity-75">{{ $notif->new_status }}</span>
                        </div>
                        <div class="ms-auto">
                            <strong>Oleh:</strong> 
                            <span class="text-white">{{ $notif->user ? $notif->user->name : 'Sistem' }}</span>
                        </div>
                    </div>
                </li>
            @empty
                <li class="list-group-item bg-dark text-center py-5 text-light-emphasis border-0">
                    <i class="bi bi-bell-slash display-4 mb-3 d-block text-secondary"></i>
                    Belum ada notifikasi atau log yang masuk.
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
