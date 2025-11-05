<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\DocumentController;

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes (Check session)
Route::middleware(['web'])->group(function () {
    // Branch switching
    Route::get('/switch-branch/{id}', function ($id) {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $branch = \DB::table('branches')->find($id);
        if ($branch && $branch->is_active) {
            session([
                'current_branch_id' => $branch->id,
                'current_branch_name' => $branch->name,
                'current_branch_code' => $branch->code
            ]);
        }

        return redirect()->back()->with('success', 'Branch switched to: ' . $branch->name);
    })->name('switch.branch');

    Route::get('/dashboard', function () {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Get dashboard data
        $stats = [
            'total_shipments' => \DB::table('shipments')->count(),
            'in_transit' => \DB::table('shipments')->where('status', 'in_transit')->count(),
            'delivered' => \DB::table('shipments')->where('status', 'delivered')->count(),
            'total_customers' => \DB::table('customers')->count(),
            'pending_invoices' => \DB::table('invoices')->where('status', 'sent')->count(),
            'total_revenue' => \DB::table('invoices')->sum('total_amount'),
        ];

        $recent_shipments = \DB::table('shipments')
            ->join('customers', 'shipments.customer_id', '=', 'customers.id')
            ->select('shipments.*', 'customers.company_name')
            ->orderBy('shipments.created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recent_shipments'));
    })->name('dashboard');

    // Shipments
    Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create', [ShipmentController::class, 'create'])->name('shipments.create');
    Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('/shipments/{id}', [ShipmentController::class, 'show'])->name('shipments.show');
    Route::get('/shipments/{id}/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
    Route::put('/shipments/{id}', [ShipmentController::class, 'update'])->name('shipments.update');
    Route::delete('/shipments/{id}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');

    // Documents
    Route::post('/shipments/{id}/documents', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{id}/view', [DocumentController::class, 'view'])->name('documents.view');
    Route::delete('/documents/{id}', [DocumentController::class, 'delete'])->name('documents.delete');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{id}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    // Staff Management
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/salaries', [StaffController::class, 'salaries'])->name('staff.salaries');
    Route::get('/staff/roles', [StaffController::class, 'roles'])->name('staff.roles');
    Route::get('/staff/{id}', [StaffController::class, 'show'])->name('staff.show');
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');

    // Finance Management
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/revenue', [FinanceController::class, 'revenue'])->name('finance.revenue');
    Route::get('/finance/expenses', [FinanceController::class, 'expenses'])->name('finance.expenses');
    Route::get('/finance/expenses/create', [FinanceController::class, 'createExpense'])->name('finance.expenses.create');
    Route::post('/finance/expenses', [FinanceController::class, 'storeExpense'])->name('finance.expenses.store');
    Route::get('/finance/reports', [FinanceController::class, 'reports'])->name('finance.reports');

    // Settings Management
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/system', [SettingsController::class, 'system'])->name('settings.system');
    Route::get('/settings/branches', [SettingsController::class, 'branches'])->name('settings.branches');
    Route::post('/settings/branches', [SettingsController::class, 'storeBranch'])->name('settings.branches.store');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/system', [SettingsController::class, 'updateSystem'])->name('settings.system.update');
    Route::get('/settings/dynamic-options', [SettingsController::class, 'dynamicOptions'])->name('settings.dynamic-options');
    Route::post('/settings/dynamic-options', [SettingsController::class, 'storeDynamicOption'])->name('settings.dynamic-options.store');
    Route::put('/settings/dynamic-options/{id}', [SettingsController::class, 'updateDynamicOption'])->name('settings.dynamic-options.update');
    Route::delete('/settings/dynamic-options/{id}', [SettingsController::class, 'deleteDynamicOption'])->name('settings.dynamic-options.delete');

    // Reports Management
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/shipments', [ReportsController::class, 'shipments'])->name('reports.shipments');
    Route::get('/reports/customers', [ReportsController::class, 'customers'])->name('reports.customers');
    Route::get('/reports/financial', [ReportsController::class, 'financial'])->name('reports.financial');
    Route::get('/reports/staff', [ReportsController::class, 'staff'])->name('reports.staff');
});
