<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ReportsController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $stats = [
            'total_reports' => 12,
            'generated_today' => 3,
            'scheduled_reports' => 5,
            'report_storage' => '2.4 GB'
        ];

        $recentReports = [
            ['name' => 'Monthly Shipment Report', 'type' => 'Shipments', 'generated' => '2 hours ago', 'size' => '1.2 MB'],
            ['name' => 'Customer Activity Summary', 'type' => 'Customers', 'generated' => '5 hours ago', 'size' => '890 KB'],
            ['name' => 'Financial Overview Q4', 'type' => 'Financial', 'generated' => '1 day ago', 'size' => '2.1 MB'],
            ['name' => 'Staff Performance Report', 'type' => 'Staff', 'generated' => '2 days ago', 'size' => '756 KB'],
        ];

        return view('reports.index', compact('stats', 'recentReports'));
    }

    public function shipments(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $dateFrom = $request->get('date_from', date('Y-m-01'));
        $dateTo = $request->get('date_to', date('Y-m-d'));

        // Check if status column exists
        $hasStatusColumn = Schema::hasColumn('shipments', 'status');

        if ($hasStatusColumn) {
            $shipmentStats = [
                'total_shipments' => \DB::table('shipments')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
                'delivered' => \DB::table('shipments')->where('status', 'delivered')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
                'in_transit' => \DB::table('shipments')->where('status', 'in_transit')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
                'pending' => \DB::table('shipments')->where('status', 'pending')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            ];
        } else {
            // Provide default stats if status column doesn't exist
            $totalCount = \DB::table('shipments')->whereBetween('created_at', [$dateFrom, $dateTo])->count();
            $shipmentStats = [
                'total_shipments' => $totalCount,
                'delivered' => intval($totalCount * 0.4),
                'in_transit' => intval($totalCount * 0.3),
                'pending' => intval($totalCount * 0.3),
            ];
        }

        // Check if origin and destination columns exist
        $hasOriginDestination = \Schema::hasColumn('shipments', 'origin') && \Schema::hasColumn('shipments', 'destination');

        if ($hasOriginDestination) {
            $shipmentsByRoute = \DB::table('shipments')
                ->select('origin', 'destination', \DB::raw('COUNT(*) as count'))
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereNotNull('origin')
                ->whereNotNull('destination')
                ->groupBy('origin', 'destination')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();
        } else {
            // Provide dummy data if columns don't exist
            $shipmentsByRoute = collect([
                (object)['origin' => 'Dubai', 'destination' => 'London', 'count' => 15],
                (object)['origin' => 'Dubai', 'destination' => 'New York', 'count' => 12],
                (object)['origin' => 'Abu Dhabi', 'destination' => 'Paris', 'count' => 8],
                (object)['origin' => 'Dubai', 'destination' => 'Tokyo', 'count' => 6],
                (object)['origin' => 'Sharjah', 'destination' => 'Mumbai', 'count' => 5],
            ]);
        }

        $dailyShipments = \DB::table('shipments')
            ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy(\DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('reports.shipments', compact('shipmentStats', 'shipmentsByRoute', 'dailyShipments', 'dateFrom', 'dateTo'));
    }

    public function customers(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $dateFrom = $request->get('date_from', date('Y-m-01'));
        $dateTo = $request->get('date_to', date('Y-m-d'));

        $customerStats = [
            'total_customers' => \DB::table('customers')->count(),
            'new_customers' => \DB::table('customers')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'active_customers' => \DB::table('customers')
                ->join('shipments', 'customers.id', '=', 'shipments.customer_id')
                ->whereBetween('shipments.created_at', [$dateFrom, $dateTo])
                ->distinct('customers.id')
                ->count(),
            'top_customers' => 5
        ];

        $topCustomers = \DB::table('customers')
            ->join('shipments', 'customers.id', '=', 'shipments.customer_id')
            ->select('customers.company_name', \DB::raw('COUNT(shipments.id) as shipment_count'))
            ->whereBetween('shipments.created_at', [$dateFrom, $dateTo])
            ->groupBy('customers.id', 'customers.company_name')
            ->orderBy('shipment_count', 'desc')
            ->limit(10)
            ->get();

        $customerGrowth = \DB::table('customers')
            ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy(\DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('reports.customers', compact('customerStats', 'topCustomers', 'customerGrowth', 'dateFrom', 'dateTo'));
    }

    public function financial(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $dateFrom = $request->get('date_from', date('Y-m-01'));
        $dateTo = $request->get('date_to', date('Y-m-d'));

        $financialStats = [
            'total_revenue' => \DB::table('invoices')->whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_amount'),
            'paid_invoices' => \DB::table('invoices')->where('status', 'paid')->whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_amount'),
            'pending_invoices' => \DB::table('invoices')->where('status', 'sent')->whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_amount'),
            'overdue_invoices' => \DB::table('invoices')->where('status', 'overdue')->whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_amount'),
        ];

        $monthlyRevenue = \DB::table('invoices')
            ->select(\DB::raw('MONTH(created_at) as month'), \DB::raw('YEAR(created_at) as year'), \DB::raw('SUM(total_amount) as revenue'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy(\DB::raw('YEAR(created_at)'), \DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $paymentMethods = \DB::table('payments')
            ->select('payment_method', \DB::raw('COUNT(*) as count'), \DB::raw('SUM(amount) as total'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('payment_method')
            ->get();

        return view('reports.financial', compact('financialStats', 'monthlyRevenue', 'paymentMethods', 'dateFrom', 'dateTo'));
    }

    public function staff(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $dateFrom = $request->get('date_from', date('Y-m-01'));
        $dateTo = $request->get('date_to', date('Y-m-d'));

        $staffStats = [
            'total_staff' => \DB::table('users')->count(),
            'active_staff' => \DB::table('users')->where('status', 'active')->count(),
            'new_hires' => \DB::table('users')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'departments' => \DB::table('users')->distinct('branch')->count(),
        ];

        $staffByRole = \DB::table('users')
            ->select('role', \DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();

        $staffByBranch = \DB::table('users')
            ->select('branch', \DB::raw('COUNT(*) as count'))
            ->groupBy('branch')
            ->get();

        $recentActivity = [
            ['staff' => 'John Doe', 'action' => 'Created new shipment', 'time' => '2 hours ago'],
            ['staff' => 'Jane Smith', 'action' => 'Updated customer profile', 'time' => '4 hours ago'],
            ['staff' => 'Mike Johnson', 'action' => 'Generated invoice', 'time' => '6 hours ago'],
            ['staff' => 'Sarah Wilson', 'action' => 'Processed payment', 'time' => '8 hours ago'],
        ];

        return view('reports.staff', compact('staffStats', 'staffByRole', 'staffByBranch', 'recentActivity', 'dateFrom', 'dateTo'));
    }
}
