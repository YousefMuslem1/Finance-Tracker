<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\Commission;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransictionsRequest\StoreTransactionRequest;
use App\Http\Requests\TransictionsRequest\UpdateTransactionRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class TransicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['category', 'commission']);

        // Filter by Type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        } elseif ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('created_at', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Amount Range
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // Filter by Commission Range
        if ($request->filled('min_commission')) {
            $query->whereHas('commission', function ($q) use ($request) {
                $q->where('amount', '>=', $request->min_commission);
            });
        }
        if ($request->filled('max_commission')) {
            $query->whereHas('commission', function ($q) use ($request) {
                $q->where('amount', '<=', $request->max_commission);
            });
        }

        $transictions = $query->latest()->paginate(10);

        $categories = Category::all();

        return view('pages.transictions.index', compact('transictions', 'categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;

            $transaction = Transaction::create($validatedData);

            if ($request->filled('commission') && $request->commission > 0) {
                Commission::create([
                    'transaction_id' => $transaction->id,
                    'amount' => $request->commission,
                ]);
            }

            DB::commit();

            return redirect()->route('transications.index')->with('success', 'New transaction added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transications.index')->with('error', 'Transaction failed');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);

            $validatedData = $request->validated();
            $validatedData['category_id'] = $validatedData['category'];
            $transaction->update($validatedData);

            if ($request->filled('commission') && $request->commission > 0) {
                $transaction->commission()->updateOrCreate(
                    ['transaction_id' => $transaction->id],
                    ['amount' => $request->commission]
                );
            }

            DB::commit();

            return redirect()->route('transications.index')->with('success', 'Transaction updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transications.index')->with('error', 'Transaction update failed');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            DB::beginTransaction();

            // حذف العمولة المرتبطة
            $transaction->commission()->delete();

            // حذف العملية
            $transaction->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Transaction deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete transaction.'], 500);
        }
    }


    public function exportPdf(Request $request)
    {

        $type = $request->get('type');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $category_id = $request->get('category_id');
        $min_amount = $request->get('min_amount');
        $max_amount = $request->get('max_amount');
        $min_commission = $request->get('min_commission');
        $max_commission = $request->get('max_commission');

        // بناء استعلام للبحث مع الفلاتر
        $transactionsQuery = Transaction::with(['category', 'commission']);

        if ($type) {
            $transactionsQuery->where('type', $type);
        }
        if ($start_date && $end_date) {
            $transactionsQuery->whereBetween('created_at', [$start_date, $end_date]);
        }
        if ($category_id) {
            $transactionsQuery->where('category_id', $category_id);
        }
        if ($min_amount) {
            $transactionsQuery->where('amount', '>=', $min_amount);
        }
        if ($max_amount) {
            $transactionsQuery->where('amount', '<=', $max_amount);
        }
        if ($min_commission) {
            $transactionsQuery->whereHas('commission', function ($query) use ($min_commission) {
                $query->where('amount', '>=', $min_commission);
            });
        }
        if ($max_commission) {
            $transactionsQuery->whereHas('commission', function ($query) use ($max_commission) {
                $query->where('amount', '<=', $max_commission);
            });
        }

        $transactions = $transactionsQuery->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $totalCommission = $transactions->sum(function ($transaction) {
            return $transaction->commission ? $transaction->commission->amount : 0;
        });
        $netAmount = $totalIncome - $totalExpense - $totalCommission;

        $pdf = PDF::loadView('pages.transictions.pdf', compact('transactions', 'totalIncome', 'totalExpense', 'totalCommission', 'netAmount'));

        return $pdf->download('transactions_report.pdf');
    }
}
