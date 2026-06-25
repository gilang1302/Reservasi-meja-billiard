@extends('layouts.bootstrap')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <h2 class="mb-4 text-white font-weight-bold">
            <i class="bi bi-calendar3 text-success me-2"></i>Kalender Jadwal Reservasi
        </h2>

        <!-- Legend -->
        <div class="card card-cue mb-4">
            <div class="card-body py-3 d-flex flex-wrap align-items-center gap-4">
                <div class="small fw-semibold text-light-emphasis"><i class="bi bi-info-circle me-1"></i>Legenda Status:</div>
                <div class="d-flex align-items-center gap-2">
                    <span style="display: inline-block; width: 16px; height: 16px; background-color: #198754; border-radius: 4px;"></span>
                    <span class="small text-white">Confirmed (Aktif & Lunas)</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span style="display: inline-block; width: 16px; height: 16px; background-color: #ffc107; border-radius: 4px;"></span>
                    <span class="small text-white">Pending (Menunggu Bayar)</span>
                </div>
                <div class="ms-auto text-light-emphasis small">
                    *Reservasi yang dibatalkan tidak ditampilkan di kalender.
                </div>
            </div>
        </div>

        <!-- FullCalendar Display -->
        <div class="card card-cue p-4">
            <div class="card-body">
                <div id="calendar" style="min-height: 600px; color: #fff;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Customize FullCalendar to match dark premium theme */
    .fc {
        --fc-border-color: #334155;
        --fc-button-bg-color: #059669;
        --fc-button-border-color: #059669;
        --fc-button-hover-bg-color: #10b981;
        --fc-button-hover-border-color: #10b981;
        --fc-button-active-bg-color: #047857;
        --fc-button-active-border-color: #047857;
        --fc-today-bg-color: rgba(16, 185, 129, 0.08);
        --fc-neutral-bg-color: #0f172a;
    }
    
    .fc .fc-toolbar-title {
        color: #f8fafc;
        font-weight: 700;
    }
    
    .fc-col-header-cell-cushion, .fc-daygrid-day-number {
        color: #f8fafc;
        text-decoration: none;
    }
    
    .fc-event {
        cursor: pointer;
        padding: 2px 5px;
        font-weight: 500;
        font-size: 0.85rem;
        border: none !important;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    
    .fc-theme-standard th {
        background-color: #1e293b;
        padding: 8px 0;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari'
            },
            events: "{{ route('reservations.calendar.data') }}",
            eventClick: function(info) {
                // Show modal or alert with details
                const props = info.event.extendedProps;
                
                let detailsText = `Meja Billiard: Meja ${props.table_number}\n`;
                detailsText += `Pelanggan: ${props.customer}\n`;
                detailsText += `Status: ${props.status}\n`;
                detailsText += `Mulai: ${info.event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}\n`;
                detailsText += `Selesai: ${info.event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}`;
                
                alert("Rincian Reservasi:\n" + detailsText);
            }
        });
        
        calendar.render();
    });
</script>
@endsection
