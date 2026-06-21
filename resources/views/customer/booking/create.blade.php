@extends('layouts.app')

@section('title', 'Konfirmasi Reservasi')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <!-- Header -->
    <div>
        <a href="{{ route('booking.meja') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 mb-2 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Denah Meja
        </a>
        <h2 class="text-2xl font-bold text-slate-100">Konfirmasi Reservasi Meja</h2>
        <p class="text-sm text-slate-400 mt-1">Langkah terakhir sebelum mengamankan slot bermain Anda.</p>
    </div>

    @if(!$availability['available'])
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation text-lg mt-0.5"></i>
            <div>
                <span class="font-bold text-sm">Meja Tidak Tersedia!</span>
                <p class="text-xs text-red-300 mt-0.5">{{ $availability['message'] }}</p>
                <a href="{{ route('booking.meja') }}" class="text-xs font-bold text-white underline mt-2 block">Pilih Meja / Waktu Lain</a>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Summary Info (Left/Top) -->
        <div class="md:col-span-2 space-y-6">
            <!-- Table Info Card -->
            <div class="p-6 rounded-3xl glass-panel border border-slate-800/80">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Informasi Meja</h3>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-slate-800 border border-slate-700/80 flex items-center justify-center text-[#6C3BFF]">
                        <i class="fa-solid fa-rectangle-ad text-3xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-slate-100">Meja #{{ $table->table_number }}</h4>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-purple-900/50 text-purple-300 border border-purple-800/30">
                            {{ $table->table_type }}
                        </span>
                        <p class="text-xs text-slate-400 mt-1 italic">{{ $table->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Schedule Info Card -->
            <div class="p-6 rounded-3xl glass-panel border border-slate-800/80">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Waktu Reservasi</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-xs text-slate-500 uppercase font-semibold">Tanggal</span>
                        <span class="font-bold text-slate-300">{{ $startTime->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 uppercase font-semibold">Durasi</span>
                        <span class="font-bold text-slate-300">{{ $durationHours }} Jam</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 uppercase font-semibold">Mulai</span>
                        <span class="font-bold text-slate-300">{{ $startTime->format('H:i') }} WIB</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 uppercase font-semibold">Selesai</span>
                        <span class="font-bold text-slate-300">{{ $endTime->format('H:i') }} WIB</span>
                    </div>
                </div>
            </div>

            <!-- Equipment Rental Selection -->
            @php
                $rentableItems = \App\Models\Inventory::where('quantity_available', '>', 0)->where('rental_price_per_hour', '>', 0)->get();
            @endphp
            @if($rentableItems->count() > 0)
                <div x-data="{ open: false }" class="p-6 rounded-3xl glass-panel border border-slate-800/80">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                        <span class="text-sm font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                            <i class="fa-solid fa-toolbox text-[#FF6B35]"></i> Sewa Alat Tambahan (Opsional)
                        </span>
                        <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    
                    <div x-show="open" class="mt-4 space-y-4 pt-4 border-t border-slate-800/50" style="display: none;">
                        <p class="text-xs text-slate-400">Pilih stik premium atau set bola eksklusif untuk disewa secara dinamis selama sesi bermain:</p>
                        
                        <div class="space-y-2">
                            @foreach($rentableItems as $item)
                                <label class="flex items-center justify-between p-3 rounded-xl bg-slate-900/60 border border-slate-850 hover:border-slate-700/60 cursor-pointer select-none">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" 
                                               class="rented-item-check rounded bg-slate-950 border-slate-800 text-[#6C3BFF] focus:ring-0 focus:ring-offset-0"
                                               data-id="{{ $item->id }}"
                                               data-name="{{ $item->item_name }}"
                                               data-price="{{ $item->rental_price_per_hour }}"
                                               onchange="recalculateTotal()">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-200">{{ $item->item_name }}</span>
                                            <span class="block text-[10px] text-slate-500">{{ $item->category }} &bull; {{ $item->quantity_available }} tersedia</span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-extrabold text-[#FF6B35]">Rp {{ number_format($item->rental_price_per_hour, 0, ',', '.') }}/jam</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Checkout Invoice Panel (Right/Bottom) -->
        <div class="glass-panel rounded-3xl p-6 border border-slate-800/80 flex flex-col justify-between h-fit">
            <div class="space-y-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800 pb-3">Rincian Pembayaran</h3>

                <div class="space-y-4 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Harga per jam</span>
                        <span class="font-bold text-slate-200">Rp {{ number_format($priceResult['per_hour'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Durasi sewa</span>
                        <span class="font-bold text-slate-200">{{ $durationHours }} Jam</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Skema Harga</span>
                        <span class="font-bold text-yellow-400 uppercase">{{ str_replace('_', ' ', $priceResult['type']) }}</span>
                    </div>

                    <!-- Member Discount (if applicable) -->
                    @if(auth()->user()->member_discount > 0 && $priceResult['type'] === 'member discount')
                        <div class="flex justify-between p-2 rounded bg-purple-950/20 text-purple-400 border border-purple-900/30">
                            <span>Diskon Member ({{ auth()->user()->member_level }})</span>
                            <span class="font-extrabold">-{{ auth()->user()->member_discount * 100 }}%</span>
                        </div>
                    @endif

                    <div class="border-t border-slate-800/50 pt-4 flex justify-between">
                        <span class="text-slate-400">Sewa Meja</span>
                        <span class="font-extrabold text-slate-200" id="base-price-val">Rp {{ number_format($priceResult['total'], 0, ',', '.') }}</span>
                    </div>

                    <!-- Dynamic Equipment Rental Row -->
                    <div class="flex justify-between text-slate-400 hidden" id="equipment-row">
                        <span>Sewa Alat Tambahan</span>
                        <span class="font-extrabold text-slate-200" id="equipment-price-val">Rp 0</span>
                    </div>

                    <!-- Total Invoice Glow -->
                    <div class="border-t border-slate-800 pt-4 flex flex-col gap-1">
                        <span class="text-[10px] uppercase font-bold text-slate-500">Estimasi Total Tagihan</span>
                        <span class="text-2xl font-extrabold text-[#FF6B35] glow-orange-text" id="total-price-val">Rp {{ number_format($priceResult['total'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Proceed Form -->
            <form method="POST" action="{{ route('booking.store') }}" id="booking-form" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="table_id" value="{{ $table->id }}">
                <input type="hidden" name="start_time" value="{{ $startTime->toDateTimeString() }}">
                <input type="hidden" name="end_time" value="{{ $endTime->toDateTimeString() }}">
                <input type="hidden" name="notes" id="notes-hidden">

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Catatan Tambahan</label>
                    <textarea id="notes-input" placeholder="Contoh: Meja disetting bola 9, stik pro dll." rows="2"
                              class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-slate-300 text-xs focus:border-[#6C3BFF] focus:ring-0 resize-none"></textarea>
                </div>

                @if($availability['available'])
                    <button type="submit" onclick="submitBookingForm(event)" class="w-full py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 hover:from-green-600 hover:to-emerald-600 text-white text-sm font-bold shadow-lg shadow-emerald-950/40 flex items-center justify-center gap-2 transition-all duration-300">
                        <i class="fa-solid fa-credit-card"></i> Buat Reservasi & Bayar
                    </button>
                @else
                    <button disabled type="button" class="w-full py-3 rounded-xl bg-slate-800 text-slate-500 text-sm font-bold border border-slate-700/60 cursor-not-allowed flex items-center justify-center gap-2">
                        <i class="fa-solid fa-ban"></i> Slot Waktu Bentrok
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
    const basePrice = {{ $priceResult['total'] }};
    const duration = {{ $durationHours }};

    function recalculateTotal() {
        let equipmentTotal = 0;
        const checkboxes = document.querySelectorAll('.rented-item-check:checked');
        
        checkboxes.forEach(cb => {
            const price = parseFloat(cb.getAttribute('data-price'));
            equipmentTotal += price * duration; // rental price per hour * duration
        });

        // Show/hide equipment row
        const eqRow = document.getElementById('equipment-row');
        if (equipmentTotal > 0) {
            eqRow.classList.remove('hidden');
            document.getElementById('equipment-price-val').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(equipmentTotal)}`;
        } else {
            eqRow.classList.add('hidden');
        }

        const grandTotal = basePrice + equipmentTotal;
        document.getElementById('total-price-val').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(grandTotal)}`;
    }

    function submitBookingForm(e) {
        e.preventDefault();
        
        // Compile notes with rented items JSON
        const notesText = document.getElementById('notes-input').value;
        const checkboxes = document.querySelectorAll('.rented-item-check:checked');
        const rentedItems = [];

        checkboxes.forEach(cb => {
            rentedItems.push({
                id: cb.getAttribute('data-id'),
                name: cb.getAttribute('data-name'),
                qty: 1,
                price: parseFloat(cb.getAttribute('data-price'))
            });
        });

        const notesData = {
            user_notes: notesText,
            rented_items: rentedItems
        };

        // Put in hidden input
        document.getElementById('notes-hidden').value = JSON.stringify(notesData);

        // Submit form
        document.getElementById('booking-form').submit();
    }
</script>
@endsection
