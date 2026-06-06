<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions =
            Transaction::all();

        return view(
            'transaction.index',
            compact('transactions')
        );
    }

    public function show($id)
    {
        $transaction =
            Transaction::findOrFail($id);

        return view(
            'transaction.show',
            compact('transaction')
        );
    }

    public function approve($id)
    {
        $transaction =
            Transaction::findOrFail($id);

        $transaction->status =
            'Paid';

        $transaction->save();

        return redirect('/transaction');
    }
}