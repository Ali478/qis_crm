<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $staff = DB::table('staff')
            ->join('branches', 'staff.branch_id', '=', 'branches.id')
            ->select('staff.*', 'branches.name as branch_name')
            ->orderBy('staff.created_at', 'desc')
            ->get();

        $stats = [
            'total_staff' => DB::table('staff')->count(),
            'active_staff' => DB::table('staff')->where('status', 'active')->count(),
            'departments' => DB::table('staff')->distinct('department')->count('department'),
            'total_salary' => DB::table('staff')->where('status', 'active')->sum('basic_salary'),
        ];

        return view('staff.index', compact('staff', 'stats'));
    }

    public function create()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $branches = DB::table('branches')->where('is_active', true)->get();

        return view('staff.create', compact('branches'));
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Generate employee ID
        $lastStaff = DB::table('staff')->orderBy('id', 'desc')->first();
        $nextNumber = $lastStaff ? ((int) substr($lastStaff->employee_id, -4) + 1) : 1;
        $employeeId = 'EMP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        DB::table('staff')->insert([
            'branch_id' => $request->branch_id ?? session('branch_id', 1),
            'employee_id' => $employeeId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'position' => $request->position,
            'hire_date' => $request->hire_date,
            'basic_salary' => $request->basic_salary,
            'allowances' => $request->allowances ?? 0,
            'employment_type' => $request->employment_type,
            'status' => 'active',
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'passport_number' => $request->passport_number,
            'visa_status' => $request->visa_status,
            'visa_expiry' => $request->visa_expiry,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone' => $request->emergency_phone,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('staff.index')->with('success', 'Employee added successfully: ' . $employeeId);
    }

    public function show($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $employee = DB::table('staff')
            ->join('branches', 'staff.branch_id', '=', 'branches.id')
            ->select('staff.*', 'branches.name as branch_name')
            ->where('staff.id', $id)
            ->first();

        if (!$employee) {
            return redirect()->route('staff.index')->with('error', 'Employee not found');
        }

        $salaryHistory = DB::table('salaries')
            ->where('staff_id', $id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('staff.show', compact('employee', 'salaryHistory'));
    }

    public function salaries()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $salaries = DB::table('salaries')
            ->join('staff', 'salaries.staff_id', '=', 'staff.id')
            ->select('salaries.*', 'staff.first_name', 'staff.last_name', 'staff.employee_id')
            ->orderBy('salaries.year', 'desc')
            ->orderBy('salaries.month', 'desc')
            ->get();

        $stats = [
            'total_paid' => DB::table('salaries')->where('payment_status', 'paid')->sum('net_salary'),
            'pending_payments' => DB::table('salaries')->where('payment_status', 'pending')->count(),
            'current_month' => DB::table('salaries')
                ->where('month', date('F'))
                ->where('year', date('Y'))
                ->sum('net_salary'),
        ];

        return view('staff.salaries', compact('salaries', 'stats'));
    }

    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $employee = DB::table('staff')
            ->join('branches', 'staff.branch_id', '=', 'branches.id')
            ->select('staff.*', 'branches.name as branch_name')
            ->where('staff.id', $id)
            ->first();

        if (!$employee) {
            return redirect()->route('staff.index')->with('error', 'Employee not found');
        }

        $branches = DB::table('branches')->where('is_active', true)->get();

        return view('staff.edit', compact('employee', 'branches'));
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $employee = DB::table('staff')->where('id', $id)->first();
        if (!$employee) {
            return redirect()->route('staff.index')->with('error', 'Employee not found');
        }

        DB::table('staff')->where('id', $id)->update([
            'branch_id' => $request->branch_id ?? $employee->branch_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'position' => $request->position,
            'hire_date' => $request->hire_date,
            'basic_salary' => $request->basic_salary,
            'allowances' => $request->allowances ?? 0,
            'employment_type' => $request->employment_type,
            'status' => $request->status ?? $employee->status,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'passport_number' => $request->passport_number,
            'visa_status' => $request->visa_status,
            'visa_expiry' => $request->visa_expiry,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone' => $request->emergency_phone,
            'updated_at' => now()
        ]);

        return redirect()->route('staff.show', $id)->with('success', 'Employee updated successfully');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $employee = DB::table('staff')->where('id', $id)->first();
        if (!$employee) {
            return redirect()->route('staff.index')->with('error', 'Employee not found');
        }

        // Soft delete by changing status to terminated
        DB::table('staff')->where('id', $id)->update([
            'status' => 'terminated',
            'updated_at' => now()
        ]);

        return redirect()->route('staff.index')->with('success', 'Employee status updated to terminated');
    }

    public function roles()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Get all roles with their permissions
        $roles = DB::table('roles')->get();

        // Get all available permissions
        $permissions = [
            'dashboard' => ['view_dashboard'],
            'shipments' => ['view_shipments', 'create_shipments', 'edit_shipments', 'delete_shipments'],
            'customers' => ['view_customers', 'create_customers', 'edit_customers', 'delete_customers'],
            'invoices' => ['view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices'],
            'staff' => ['view_staff', 'create_staff', 'edit_staff', 'delete_staff', 'manage_salaries'],
            'finance' => ['view_finance', 'manage_revenue', 'manage_expenses', 'view_reports'],
            'settings' => ['manage_settings', 'manage_branches', 'manage_roles']
        ];

        // Get role assignments
        $roleAssignments = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id', 'users.name', 'users.email', 'roles.name as role_name')
            ->get();

        return view('staff.roles', compact('roles', 'permissions', 'roleAssignments'));
    }
}