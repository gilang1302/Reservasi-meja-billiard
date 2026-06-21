<?php

namespace App\Http\Controllers;

use App\Models\BilliardTable;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TableController extends Controller
{
    /**
     * Tampilkan semua meja (Kasir — Panel Kontrol)
     */
    public function index()
    {
        $tables = BilliardTable::with('activeBooking.user')->orderBy('table_number')->get();
        return view('kasir.tables.index', compact('tables'));
    }

    /**
     * Toggle lampu meja ON/OFF (Panel Kontrol Kasir)
     */
    public function toggleLamp(string $id)
    {
        $table = BilliardTable::findOrFail($id);
        $table->toggleLamp();

        return response()->json([
            'success'     => true,
            'lamp_status' => $table->lamp_status,
            'message'     => "Lampu meja #{$table->table_number} " . ($table->lamp_status === 'on' ? 'dinyalakan' : 'dimatikan'),
        ]);
    }

    /**
     * Update status meja (kasir bisa override status)
     */
    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Available,Occupied,Booked,Maintenance',
        ]);

        $table = BilliardTable::findOrFail($id);
        $table->update(['status' => $validated['status']]);

        if ($validated['status'] === 'Available') {
            $table->update(['lamp_status' => 'off']);
        } elseif ($validated['status'] === 'Occupied') {
            $table->update(['lamp_status' => 'on']);
        }

        return back()->with('success', "Status meja #{$table->table_number} diubah ke {$validated['status']}");
    }

    /**
     * Aktivasi booking — mulai sesi bermain
     */
    public function activateBooking(string $tableId)
    {
        $table = BilliardTable::findOrFail($tableId);
        $booking = $table->activeBooking;

        if (!$booking) {
            return back()->withErrors(['error' => 'Tidak ada booking aktif untuk meja ini.']);
        }

        $booking->update(['status' => 'Active']);
        $table->transitionToOccupied();

        return back()->with('success', "Sesi bermain meja #{$table->table_number} dimulai.");
    }

    /**
     * Selesaikan sesi bermain
     */
    public function completeBooking(string $tableId)
    {
        $table   = BilliardTable::findOrFail($tableId);
        $booking = Booking::where('table_id', $tableId)
            ->where('status', 'Active')
            ->first();

        if ($booking) {
            $booking->update(['status' => 'Completed']);

            // Tambah poin ke member (Template: 10 poin/jam)
            $hours       = $booking->duration_hours;
            $pointsMultiplier = match($booking->user->member_type) {
                'Gold'     => 20,
                'Platinum' => 30,
                default    => 10,
            };
            $pointsEarned = (int) ($hours * $pointsMultiplier);

            $booking->user->increment('member_poin', $pointsEarned);
        }

        $table->transitionToAvailable();

        return back()->with('success', "Sesi bermain meja #{$table->table_number} selesai.");
    }

    // ======== Owner CRUD ========

    public function ownerIndex()
    {
        $tables = BilliardTable::withCount(['bookings as total_usage' => function ($q) {
            $q->where('status', 'Completed');
        }])->orderBy('table_number')->get();

        return view('owner.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('owner.tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number'        => 'required|string|max:5',
            'price_per_hour'      => 'required|numeric|min:0',
            'price_peak_per_hour' => 'required|numeric|min:0',
            'table_type'          => 'required|in:Standard,VIP,Tournament',
            'description'         => 'nullable|string',
            'position_x'          => 'required|integer',
            'position_y'          => 'required|integer',
        ]);

        BilliardTable::create(array_merge($validated, [
            'id'     => 'TB' . strtoupper(Str::random(9)),
            'status' => 'Available',
        ]));

        return redirect()->route('owner.tables.index')->with('success', 'Meja berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $table = BilliardTable::findOrFail($id);
        return view('owner.tables.edit', compact('table'));
    }

    public function update(Request $request, string $id)
    {
        $table = BilliardTable::findOrFail($id);

        $validated = $request->validate([
            'table_number'        => 'required|string|max:5',
            'price_per_hour'      => 'required|numeric|min:0',
            'price_peak_per_hour' => 'required|numeric|min:0',
            'table_type'          => 'required|in:Standard,VIP,Tournament',
            'description'         => 'nullable|string',
        ]);

        $table->update($validated);

        return redirect()->route('owner.tables.index')->with('success', 'Data meja berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $table = BilliardTable::findOrFail($id);
        $table->delete();

        return redirect()->route('owner.tables.index')->with('success', 'Meja berhasil dihapus.');
    }

    /**
     * Heatmap data untuk analytics
     */
    public function heatmap()
    {
        $tables = BilliardTable::withCount(['bookings as usage_count' => function ($q) {
            $q->where('status', 'Completed');
        }])->get();

        $maxUsage = $tables->max('usage_count') ?: 1;

        return view('owner.tables.heatmap', compact('tables', 'maxUsage'));
    }
}
