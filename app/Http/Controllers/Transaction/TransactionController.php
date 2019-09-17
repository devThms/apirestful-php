<?php

namespace App\Http\Controllers\Transaction;

use App\Model\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::all();

        return response()->json([
            'data' => $transactions
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return response()->json([
            'data' => $transaction
        ], 200);
    }

}
