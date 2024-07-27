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
        $transaction = Transaction::create($request->all());
        return response()->json($transaction, 201);
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

    public function getTransactionByUserId($user_id)
    {
        // Truy vấn cơ sở dữ liệu để lấy giao dịch theo user_id
        $transactions = Transaction::where('user_id', $user_id)->get();

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

    public function getTransactionByCategoryId($category_id, $user_id)
    {
        // Truy vấn cơ sở dữ liệu để lấy giao dịch theo category_id và $user_id
        $transactions = Transaction::where('category_id', $category_id)->where('user_id', $user_id)->get();

        // Trả về danh sách giao dịch dưới dạng JSON
        return response()->json($transactions, 200);
    }

    public function getTransactionByDateRange(Request $request)
    {
        // Validate the request data
        $request->validate([
            'from_date' => 'required_without:to_date|date',
            'to_date' => 'required_without:from_date|date',
            'user_id' => 'required|integer'
        ]);

        // Retrieve data from the request
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $user_id = $request->input('user_id');

        // Query the database to get transactions
        if ($from_date && $to_date) {
            // Case for date range
            $transactions = Transaction::whereBetween('transaction_date', [$from_date, $to_date])
                ->where('user_id', $user_id)
                ->get();
        } elseif ($from_date) {
            // Case for single day
            $transactions = Transaction::whereDate('transaction_date', $from_date)
                ->where('user_id', $user_id)
                ->get();
        } else {
            // Handle case where no date is provided (optional)
            return response()->json(['error' => 'Date is required'], 400);
        }

        // Return the list of transactions as JSON
        return response()->json($transactions, 200);
    }


    public function getTransactionByDate($date, $user_id)
    {
        // Truy vấn cơ sở dữ liệu để lấy giao dịch theo ngày
        $transactions = Transaction::where('transaction_date', $date)->where('user_id', $user_id)->get();

        // Trả về danh sách giao dịch dưới dạng JSON
        return response()->json($transactions, 200);
    }


}
