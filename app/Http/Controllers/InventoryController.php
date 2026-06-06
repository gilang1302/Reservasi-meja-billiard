<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories =
            Inventory::all();

        return view(
            'inventory.index',
            compact('inventories')
        );
    }

    public function store(Request $request)
    {
        Inventory::create([
            'item_name' =>
                $request->item_name,

            'quantity' =>
                $request->quantity
        ]);

        return redirect('/inventory');
    }

    public function update(Request $request, $id)
    {
        Inventory::findOrFail($id)
            ->update([
                'quantity' =>
                    $request->quantity
            ]);

        return redirect('/inventory');
    }
}