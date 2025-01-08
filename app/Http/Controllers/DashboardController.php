<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Transaction;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // جلب معرف المستخدم الحالي
    
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');
    
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');
    
        $totalCommission = Transaction::where('user_id', $userId)
            ->where('type', 'commission')
            ->sum('amount');
    
        $netAmount = $totalIncome - ($totalExpense + $totalCommission);
    
        // البيانات للرسوم البيانية
        $chartData = Transaction::where('user_id', $userId)
            ->selectRaw('DATE(created_at) as date, 
                         SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
                         SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    
        // أحدث المعاملات
        $latestTransactions = Transaction::where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();
    
        return view('dashboard', compact('totalIncome', 'totalExpense', 'totalCommission', 'netAmount', 'chartData', 'latestTransactions'));
    }
    
}
