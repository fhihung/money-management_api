<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    public function index()
    {
        return Transaction::all();
    }

    public function show($id)
    {
        return Transaction::find($id);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
            'transaction_date' => 'required|date',
            'account_id' => 'required|integer|exists:accounts,id',
            'user_id' => 'required|integer|exists:users,id',
            'category_id' => 'required|integer|exists:categories,id'
        ]);
        $transaction = Transaction::create($validatedData);

        return response()->json($transaction, 200);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());
        return response()->json($transaction, 200);
    }

    public function destroy($id)
    {
        Transaction::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    public function getTransactionByUserId(Request $request)
    {
        // Lấy user_id từ query parameters
        $user_id = $request->query('user_id');

        // Truy vấn cơ sở dữ liệu để lấy giao dịch theo user_id
        $transactions = Transaction::where('user_id', $user_id)
            ->with(['account', 'category'])
            ->get();

        // Trả về danh sách giao dịch dưới dạng JSON
        return response()->json($transactions, 200);
    }

    public function getTransactionByAccountId($account_id, $user_id)
    {
        // Truy vấn cơ sở dữ liệu để lấy giao dịch theo account_id và $user_id
        $transactions = Transaction::where('account_id', $account_id)->where('user_id', $user_id)->get();

        // Trả về danh sách giao dịch dưới dạng JSON
        return response()->json($transactions, 200);
    }


}
