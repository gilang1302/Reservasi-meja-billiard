<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();

        return view(
            'table.index',
            compact('tables')
        );
    }

    public function create()
    {
        return view('table.create');
    }

    public function store(Request $request)
    {
        Table::create([
            'table_number' => $request->table_number,
            'table_type' => $request->table_type,
            'status' => 'Available'
        ]);

        return redirect('/table');
    }

    public function updateStatus($id)
    {
        $table = Table::findOrFail($id);

        if($table->status == 'Available')
        {
            $table->status = 'Occupied';
        }
        else
        {
            $table->status = 'Available';
        }

        $table->save();

        return redirect('/table');
    }
}