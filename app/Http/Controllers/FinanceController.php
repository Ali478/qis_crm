<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Get financial overview
        $currentMonth = date('Y-m');
        $currentYear = date('Y');

        $stats = [
            'total_revenue' => DB::table('revenue')->where('status', 'completed')->sum('amount'),
            'total_expenses' => DB::table('expenses')->where('status', 'paid')->sum('amount'),
            'monthly_revenue' => DB::table('revenue')
                ->where('status', 'completed')
                ->whereRaw('DATE_FORMAT(transaction_date, "%Y-%m") = ?', [$currentMonth])
                ->sum('amount'),
            'monthly_expenses' => DB::table('expenses')
                ->where('status', 'paid')
                ->whereRaw('DATE_FORMAT(expense_date, "%Y-%m") = ?', [$currentMonth])
                ->sum('amount'),
            'pending_invoices' => DB::table('invoices')->where('status', '!=', 'paid')->sum('balance_amount'),
            'pending_expenses' => DB::table('expenses')->where('status', 'pending')->count(),
        ];

        $stats['net_profit'] = $stats['total_revenue'] - $stats['total_expenses'];
        $stats['monthly_profit'] = $stats['monthly_revenue'] - $stats['monthly_expenses'];

        // Get recent transactions
        $recentRevenue = DB::table('revenue')
            ->leftJoin('customers', 'revenue.customer_id', '=', 'customers.id')
            ->select('revenue.*', 'customers.company_name')
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        $recentExpenses = DB::table('expenses')
            ->orderBy('expense_date', 'desc')
            ->limit(5)
            ->get();

        return view('finance.index', compact('stats', 'recentRevenue', 'recentExpenses'));
    }

    public function revenue()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $revenue = DB::table('revenue')
            ->leftJoin('customers', 'revenue.customer_id', '=', 'customers.id')
            ->leftJoin('invoices', 'revenue.invoice_id', '=', 'invoices.id')
            ->select(
                'revenue.*',
                'customers.company_name',
                'invoices.invoice_number'
            )
            ->orderBy('revenue.transaction_date', 'desc')
            ->get();

        $stats = [
            'total_revenue' => DB::table('revenue')->where('status', 'completed')->sum('amount'),
            'monthly_revenue' => DB::table('revenue')
                ->where('status', 'completed')
                ->whereMonth('transaction_date', date('m'))
                ->whereYear('transaction_date', date('Y'))
                ->sum('amount'),
            'transactions' => DB::table('revenue')->count(),
        ];

        return view('finance.revenue', compact('revenue', 'stats'));
    }

    public function expenses()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $expenses = DB::table('expenses')
            ->join('users', 'expenses.submitted_by', '=', 'users.id')
            ->leftJoin('users as approver', 'expenses.approved_by', '=', 'approver.id')
            ->select(
                'expenses.*',
                'users.name as submitted_by_name',
                'approver.name as approved_by_name'
            )
            ->orderBy('expenses.expense_date', 'desc')
            ->get();

        $stats = [
            'total_expenses' => DB::table('expenses')->where('status', 'paid')->sum('amount'),
            'pending_approval' => DB::table('expenses')->where('status', 'pending')->sum('amount'),
            'monthly_expenses' => DB::table('expenses')
                ->where('status', 'paid')
                ->whereMonth('expense_date', date('m'))
                ->whereYear('expense_date', date('Y'))
                ->sum('amount'),
        ];

        $categories = [
            'office' => 'Office Supplies',
            'transport' => 'Transportation',
            'utilities' => 'Utilities',
            'maintenance' => 'Maintenance',
            'marketing' => 'Marketing',
            'rent' => 'Rent',
            'salaries' => 'Salaries',
            'other' => 'Other'
        ];

        return view('finance.expenses', compact('expenses', 'stats', 'categories'));
    }

    public function createExpense()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $branches = DB::table('branches')->where('is_active', true)->get();

        return view('finance.create-expense', compact('branches'));
    }

    public function storeExpense(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Generate expense number
        $lastExpense = DB::table('expenses')->orderBy('id', 'desc')->first();
        $nextNumber = $lastExpense ? ((int) substr($lastExpense->expense_number, -4) + 1) : 1;
        $expenseNumber = 'EXP-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        DB::table('expenses')->insert([
            'branch_id' => $request->branch_id ?? session('branch_id', 1),
            'expense_number' => $expenseNumber,
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'currency' => 'USD',
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method,
            'vendor_name' => $request->vendor_name,
            'invoice_number' => $request->invoice_number,
            'status' => 'pending',
            'submitted_by' => session('user_id', 1),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('finance.expenses')->with('success', 'Expense submitted successfully: ' . $expenseNumber);
    }

    public function reports()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Summary calculations
        $totalRevenue = DB::table('revenue')->where('status', 'completed')->sum('amount');
        $totalExpenses = DB::table('expenses')->where('status', 'paid')->sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        $summary = [
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'profit_margin' => $profitMargin,
        ];

        // Revenue breakdown by source
        $revenueBySource = DB::table('revenue')
            ->where('status', 'completed')
            ->select('source', DB::raw('SUM(amount) as total'))
            ->groupBy('source')
            ->get();

        $revenueBreakdown = [];
        foreach ($revenueBySource as $revenue) {
            $percentage = $totalRevenue > 0 ? ($revenue->total / $totalRevenue) * 100 : 0;
            $revenueBreakdown[] = [
                'source' => $revenue->source,
                'amount' => $revenue->total,
                'percentage' => round($percentage, 1)
            ];
        }

        // Expense breakdown by category
        $expenseByCategory = DB::table('expenses')
            ->where('status', 'paid')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        $expenseBreakdown = [];
        foreach ($expenseByCategory as $expense) {
            $percentage = $totalExpenses > 0 ? ($expense->total / $totalExpenses) * 100 : 0;
            $expenseBreakdown[] = [
                'category' => $expense->category,
                'amount' => $expense->total,
                'percentage' => round($percentage, 1)
            ];
        }

        // P&L Statement data
        $plStatement = [
            'shipment_revenue' => DB::table('revenue')->where('source', 'shipment')->where('status', 'completed')->sum('amount'),
            'service_fees' => DB::table('revenue')->where('source', 'service_fee')->where('status', 'completed')->sum('amount'),
            'other_revenue' => DB::table('revenue')->whereIn('source', ['invoice', 'other'])->where('status', 'completed')->sum('amount'),
            'operational_expenses' => DB::table('expenses')->whereIn('category', ['transport', 'maintenance', 'utilities'])->where('status', 'paid')->sum('amount'),
            'staff_salaries' => DB::table('salaries')->where('payment_status', 'paid')->sum('net_salary'),
            'admin_expenses' => DB::table('expenses')->whereIn('category', ['office', 'marketing', 'rent', 'other'])->where('status', 'paid')->sum('amount'),
        ];

        return view('finance.reports', compact('summary', 'revenueBreakdown', 'expenseBreakdown', 'plStatement'));
    }
}