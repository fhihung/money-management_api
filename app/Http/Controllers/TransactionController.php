<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
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

    public function getTransactionsByDate(Request $request)
    {
        // Validate the request data
        $request->validate([
            'start_date' => 'required_without:end_date|date',
            'end_date' => 'required_without:start_date|date',
            'user_id' => 'required|integer'
        ]);

        // Retrieve data from the request
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $user_id = $request->query('user_id');

        // Query the database to get transactions
        if ($start_date && $end_date) {
            // Case for date range
            $transactions = Transaction::whereBetween('transaction_date', [$start_date, $end_date])
                ->where('user_id', $user_id)->with(['account', 'category'])
                ->get();
        } elseif ($start_date) {
            // Case for single day
            $transactions = Transaction::whereDate('transaction_date', $start_date)
                ->where('user_id', $user_id)->with(['account', 'category'])
                ->get();
        } elseif ($end_date) {
            // Case for single day
            $transactions = Transaction::whereDate('transaction_date', $end_date)
                ->where('user_id', $user_id)->with(['account', 'category'])
                ->get();
        } else {
            // Handle case where no date is provided (optional)
            return response()->json(['error' => 'Date is required'], 400);
        }

        // Return the list of transactions as JSON
        return response()->json($transactions, 200);
    }

    public function getTransactionsForCurrentWeek(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        // Retrieve data from the request
        $user_id = $request->query('user_id');

        // Get the start and end of the current week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Query the database to get transactions
        $transactions = Transaction::whereBetween('transaction_date', [$startOfWeek, $endOfWeek])
            ->where('user_id', $user_id)->with(['account', 'category'])
            ->get();

        // Return the list of transactions as JSON
        return response()->json($transactions, 200);
    }

    public function getTransactionsForCurrentMonth(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        // Retrieve data from the request
        $user_id = $request->query('user_id');

        // Get the start and end of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Query the database to get transactions
        $transactions = Transaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->where('user_id', $user_id)->with(['account', 'category'])
            ->get();

        // Return the list of transactions as JSON
        return response()->json($transactions, 200);
    }
}
