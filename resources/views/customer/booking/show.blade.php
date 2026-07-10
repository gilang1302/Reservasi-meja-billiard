@extends('layouts.app')

@section('title', 'Rincian Reservasi')

@section('content')
@php
    // DECORATOR PATTERN: Hitung total biaya (Table + F&B + Equipment) menggunakan decorator stack
    $costBuilder = new \App\Decorators\BaseBookingCost($booking);
    $costBuilder = new \App\Decorators\FnBDecorator($costBuilder);
    $costBuilder = new \App\Decorators\EquipmentRentalDecorator($costBuilder);
    
    $totalCalculatedCost = $costBuilder->calculateCost();
    $costDescription = $costBuilder->getDescription();

    // Parse rented items from notes JSON
    $rentedItems = [];
    $userNotes = '';
    if ($booking->notes) {
        $notesData = json_decode($booking->notes, true);
        if (is_array($notesData)) {
            $userNotes = $notesData['user_notes'] ?? '';
            $rentedItems = $notesData['rented_items'] ?? [];
        } else {
            $userNotes = $booking->notes;
        }
    }
@endphp

<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('booking.history') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
            </a>
            <h2 class="text-2xl font-bold text-slate-100">Detail Reservasi #{{ $booking->id }}</h2>
            <p class="text-sm text-slate-400 mt-1">Dibuat pada {{ $booking->created_at->format('d M Y H:i') }}</p>
        </div>
        
        <!-- Status Badge -->
        <div>
            @if($booking->status === 'Pending')
                <span class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 glow-orange">Menunggu Pembayaran</span>
            @elseif($booking->status === 'Confirmed')
                <span class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase bg-blue-500/10 text-blue-400 border border-blue-500/20">Dikonfirmasi</span>
            @elseif($booking->status === 'Active')
                <span class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase bg-purple-500/10 text-purple-400 border border-purple-500/20 glow-purple">Sedang Bermain</span>
            @elseif($booking->status === 'Completed')
                <span class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase bg-green-500/10 text-green-400 border border-green-500/20 glow-green">Selesai</span>
            @else
                <span class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase bg-red-500/10 text-red-400 border border-red-500/20">Dibatalkan</span>
            @endif
        </div>
    </div>

    <!-- Booking details grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Left: Summary Details -->
        <div class="md:col-span-2 space-y-6">
            
            <!-- Reservation info -->
            <div class="p-6 rounded-3xl glass-panel border border-slate-800/80 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800 pb-3">Informasi Bermain</h3>
                
                <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div>
                        <span class="block text-xs text-slate-500 uppercase font-semibold">Meja Billiard</span>
                        <span class="font-bold text-slate-200">Meja #{{ $booking->table->table_number }} ({{ $booking->table->table_type }})</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 uppercase font-semibold">Durasi Bermain</span>
                        <span class="font-bold text-slate-200">{{ $booking->duration_hours }} Jam</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 uppercase font-semibold">Mulai Bermain</span>
                        <span class="font-bold text-slate-200">{{ $booking->start_time->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 uppercase font-semibold">Selesai Bermain</span>
                        <span class="font-bold text-slate-200">{{ $booking->end_time->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>

                @if($userNotes)
                    <div class="mt-4 p-3 bg-slate-950/40 rounded-xl border border-slate-905 text-xs">
                        <span class="font-bold text-slate-400 block mb-1">Catatan Anda:</span>
                        <p class="text-slate-300 italic">"{{ $userNotes }}"</p>
                    </div>
                @endif
            </div>

            <!-- F&B Order List for this session -->
            @if($booking->status === 'Confirmed' || $booking->status === 'Active' || $booking->status === 'Completed')
                <div class="p-6 rounded-3xl glass-panel border border-slate-800/80 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Pesanan F&B Anda</h3>
                        @if($booking->status === 'Confirmed' || $booking->status === 'Active')
                            <a href="{{ route('order.menu', $booking->id) }}" class="px-3 py-1.5 rounded-lg bg-orange-600 hover:bg-orange-500 text-white text-[11px] font-bold transition-colors">
                                <i class="fa-solid fa-plus mr-1"></i> Pesan Baru
                            </a>
                        @endif
                    </div>

                    @php
                        $orders = \App\Models\Order::where('booking_id', $booking->id)->with('details')->get();
                    @endphp
                    @if($orders->count() > 0)
                        <div class="divide-y divide-slate-800/50">
                            @foreach($orders as $order)
                                <div class="py-3 first:pt-0 last:pb-0 space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-slate-300">ID Order: #{{ $order->id }}</span>
                                        <span class="text-slate-500">{{ $order->created_at->format('H:i') }}</span>
                                        <div>
                                            @if($order->status === 'Pending')
                                                <span class="px-2 py-0.5 rounded text-[10px] bg-yellow-950 text-yellow-400 border border-yellow-800/30">Menunggu</span>
                                            @elseif($order->status === 'Processing')
                                                <span class="px-2 py-0.5 rounded text-[10px] bg-blue-950 text-blue-400 border border-blue-800/30">Diproses</span>
                                            @elseif($order->status === 'Ready')
                                                <span class="px-2 py-0.5 rounded text-[10px] bg-purple-950 text-purple-400 border border-purple-800/30">Siap Diantar</span>
                                            @elseif($order->status === 'Delivered')
                                                <span class="px-2 py-0.5 rounded text-[10px] bg-green-950 text-green-400 border border-green-800/30">Diterima</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] bg-red-950 text-red-400 border border-red-800/30">Batal</span>
                                            @endif
                                        </div>
                                    </div>
                                    <ul class="space-y-1 pl-4 list-disc text-xs text-slate-450">
                                        @foreach($order->details as $det)
                                            <li>{{ $det->item_name }} ({{ $det->quantity }}x)</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-500 py-2">Belum ada pesanan F&B. Pesan makanan/minuman langsung dari meja billiard Anda.</p>
                    @endif
                </div>
            @endif

            <!-- Rented Items (Equipment) list -->
            @if(count($rentableItems = $rentedItems) > 0)
                <div class="p-6 rounded-3xl glass-panel border border-slate-800/80 space-y-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800 pb-3">Alat yang Disewa</h3>
                    <div class="space-y-2">
                        @foreach($rentableItems as $item)
                            <div class="flex items-center justify-between text-xs py-1 text-slate-300">
                                <span><i class="fa-solid fa-toolbox text-[#FF6B35] mr-2"></i>{{ $item['name'] }}</span>
                                <span class="font-semibold">Rp {{ number_format($item['price'], 0, ',', '.') }}/jam</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Invoice & Payment -->
        <div class="space-y-6">
            
            <!-- Invoice Details -->
            <div class="p-6 rounded-3xl glass-panel border border-slate-800/80 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800 pb-3">Rincian Invoice</h3>
                
                <!-- Display Decorator Structure description -->
                <div class="p-2.5 rounded bg-slate-900/60 border border-slate-800 text-[10px] text-purple-400 font-semibold tracking-wider uppercase">
                    <i class="fa-solid fa-code mr-1"></i> Decorator Pattern Stack:<br>
                    <span class="text-slate-400 font-mono block mt-1 normal-case font-medium">{{ $costDescription }}</span>
                </div>

                <div class="space-y-3.5 text-xs pt-2">
                    <div class="flex justify-between text-slate-400">
                        <span>Sewa Meja (Base)</span>
                        <span class="font-semibold text-slate-200">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>

                    @php
                        $rentedCost = 0;
                        foreach ($rentedItems as $item) {
                            $rentedCost += $item['price'] * $booking->duration_hours;
                        }
                    @endphp
                    @if($rentedCost > 0)
                        <div class="flex justify-between text-slate-400">
                            <span>Sewa Alat</span>
                            <span class="font-semibold text-slate-200">Rp {{ number_format($rentedCost, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @php
                        $ordersTotal = \App\Models\Order::where('booking_id', $booking->id)->where('status', '!=', 'Cancelled')->sum('total_price');
                    @endphp
                    @if($ordersTotal > 0)
                        <div class="flex justify-between text-slate-400">
                            <span>Tagihan F&B</span>
                            <span class="font-semibold text-slate-200">Rp {{ number_format($ordersTotal, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="border-t border-slate-800/50 pt-3 flex justify-between font-bold text-sm text-slate-100">
                        <span>Total Tagihan</span>
                        <span class="text-[#FF6B35]">Rp {{ number_format($totalCalculatedCost, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Widget for Pending state -->
            @if($booking->status === 'Pending')
                <div class="p-6 rounded-3xl border border-yellow-500/20 bg-yellow-500/5 space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-yellow-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-wallet"></i> Selesaikan Pembayaran
                    </h3>
                    <p class="text-xs text-slate-400">Silakan lakukan transfer bank manual ke rekening berikut:</p>
                    
                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Bank</span>
                            <span class="font-bold text-slate-300">BCA (CueMaster Reserve)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">No. Rekening</span>
                            <span class="font-extrabold text-blue-400 tracking-wider">123-456-7890</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Jumlah Transfer</span>
                            <span class="font-extrabold text-[#FF6B35]">Rp {{ number_format($totalCalculatedCost, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('transaction.payment', $booking->id) }}" class="w-full py-2.5 rounded-xl bg-[#FF6B35] hover:bg-orange-500 text-white text-xs font-bold shadow-lg shadow-orange-950/40 flex items-center justify-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Bukti Transfer
                    </a>

                    <form method="POST" action="{{ route('booking.store') }}/../{{ $booking->id }}/cancel" class="pt-2 border-t border-slate-850">
                        @csrf
                        <button type="submit" class="w-full text-center text-xs text-red-400 hover:text-red-300 hover:underline bg-transparent border-0 font-semibold cursor-pointer">
                            Batalkan Reservasi
                        </button>
                    </form>
                </div>
            @endif

            <!-- Extend play session for Active state -->
            @if($booking->status === 'Active')
                <div class="p-6 rounded-3xl border border-purple-500/20 bg-purple-500/5 space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-purple-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-clock"></i> Perpanjang Durasi Main
                    </h3>
                    <p class="text-xs text-slate-400">Anda dapat memperpanjang durasi bermain Anda langsung dari web jika tidak ada booking bentrok berikutnya.</p>
                    
                    @if($booking->canExtend())
                        <form method="POST" action="{{ route('booking.extend', $booking->id) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Tambahan Waktu</label>
                                <select name="extra_minutes" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-slate-200 text-xs focus:ring-0">
                                    <option value="60">1 Jam (Rp {{ number_format($booking->table->price_per_hour, 0, ',', '.') }})</option>
                                    <option value="120">2 Jam (Rp {{ number_format($booking->table->price_per_hour * 2, 0, ',', '.') }})</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full py-2.5 rounded-xl bg-[#6C3BFF] hover:bg-indigo-600 text-white text-xs font-bold shadow-lg shadow-purple-950/40 flex items-center justify-center gap-1.5 transition-colors">
                                <i class="fa-solid fa-arrow-rotate-right"></i> Perpanjang Sekarang
                            </button>
                        </form>
                    @else
                        <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 text-[11px] text-slate-500 font-medium text-center">
                            <i class="fa-solid fa-ban text-red-500 mr-1"></i> Tidak dapat diperpanjang. Meja ini sudah dipesan oleh pengguna lain untuk slot jam berikutnya.
                        </div>
                    @endif
                </div>
            @endif

            <!-- Feedback for Completed state -->
            @if($booking->status === 'Completed')
                <div class="p-6 rounded-3xl border border-green-500/20 bg-green-500/5 space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-green-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-star-half-stroke"></i> Feedback & Review
                    </h3>
                    
                    @if($booking->hasFeedback())
                        <div class="p-3 bg-slate-900/60 rounded-xl border border-slate-800 text-xs space-y-2">
                            <div class="flex items-center text-yellow-400">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fa-{{ $i <= $booking->feedback->rating ? 'solid' : 'regular' }} fa-star text-xs"></i>
                                @endfor
                                <span class="ml-2 font-bold text-slate-200">({{ $booking->feedback->rating }}/5)</span>
                            </div>
                            <p class="text-slate-400 italic">"{{ $booking->feedback->comment }}"</p>
                        </div>
                    @else
                        <p class="text-xs text-slate-400">Berikan feedback dan rating Anda untuk membantu kami meningkatkan kualitas layanan!</p>
                        <a href="{{ route('feedback.create', $booking->id) }}" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-emerald-600 hover:to-green-600 text-white text-xs font-bold shadow-lg shadow-emerald-950/40 flex items-center justify-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-pen-to-square"></i> Tulis Umpan Balik
                        </a>
                    @endif
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
