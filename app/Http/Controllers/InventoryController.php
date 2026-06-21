<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::orderBy('category')->orderBy('item_name')->paginate(15);
        $stats = [
            'total_items'    => Inventory::count(),
            'good'           => Inventory::where('status', 'Good')->count(),
            'damaged'        => Inventory::where('status', 'Damaged')->count(),
            'lost'           => Inventory::where('status', 'Lost')->count(),
        ];
        return view('owner.inventory.index', compact('inventory', 'stats'));
    }

    public function create()
    {
        return view('owner.inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name'              => 'required|string|max:100',
            'category'               => 'required|in:Cue,Ball,Chalk,Rack,Accessory',
            'quantity'               => 'required|integer|min:1',
            'quantity_available'     => 'required|integer|min:0',
            'status'                 => 'required|in:Good,Damaged,Lost',
            'price'                  => 'required|numeric|min:0',
            'rental_price_per_hour'  => 'required|numeric|min:0',
            'notes'                  => 'nullable|string',
        ]);

        Inventory::create(array_merge($validated, [
            'id' => 'IV' . strtoupper(Str::random(9)),
        ]));

        return redirect()->route('owner.inventory.index')->with('success', 'Item inventaris berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $item = Inventory::findOrFail($id);
        return view('owner.inventory.edit', compact('item'));
    }

    public function update(Request $request, string $id)
    {
        $item = Inventory::findOrFail($id);
        $validated = $request->validate([
            'item_name'              => 'required|string|max:100',
            'category'               => 'required|in:Cue,Ball,Chalk,Rack,Accessory',
            'quantity'               => 'required|integer|min:1',
            'quantity_available'     => 'required|integer|min:0',
            'status'                 => 'required|in:Good,Damaged,Lost',
            'price'                  => 'required|numeric|min:0',
            'rental_price_per_hour'  => 'required|numeric|min:0',
            'notes'                  => 'nullable|string',
        ]);

        $item->update($validated);
        return redirect()->route('owner.inventory.index')->with('success', 'Item berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        Inventory::findOrFail($id)->delete();
        return redirect()->route('owner.inventory.index')->with('success', 'Item berhasil dihapus.');
    }
}
