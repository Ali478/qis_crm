<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Get system statistics
        $stats = [
            'total_users' => DB::table('users')->count(),
            'active_branches' => DB::table('branches')->where('is_active', true)->count(),
            'system_uptime' => '99.9%',
            'last_backup' => date('Y-m-d H:i:s'),
        ];

        // Get recent activity
        $recentActivity = [
            ['action' => 'User login', 'user' => 'Admin User', 'time' => '5 minutes ago'],
            ['action' => 'New shipment created', 'user' => 'John Manager', 'time' => '15 minutes ago'],
            ['action' => 'Invoice generated', 'user' => 'Sarah Accountant', 'time' => '1 hour ago'],
            ['action' => 'Staff member added', 'user' => 'Lisa HR', 'time' => '2 hours ago'],
        ];

        return view('settings.index', compact('stats', 'recentActivity'));
    }

    public function system()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Get system settings
        $settings = [
            'company_name' => 'Global Logistics CRM',
            'company_email' => 'info@globallogistics.com',
            'company_phone' => '+971-4-123-4567',
            'default_currency' => 'USD',
            'default_timezone' => 'Asia/Dubai',
            'date_format' => 'Y-m-d',
            'backup_frequency' => 'daily',
            'maintenance_mode' => false,
        ];

        return view('settings.system', compact('settings'));
    }

    public function branches()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $branches = DB::table('branches')
            ->leftJoin('users', 'branches.id', '=', 'users.default_branch_id')
            ->leftJoin('shipments', 'branches.id', '=', 'shipments.branch_id')
            ->leftJoin('customers', 'branches.id', '=', 'customers.branch_id')
            ->select(
                'branches.*',
                DB::raw('COUNT(DISTINCT users.id) as staff_count'),
                DB::raw('COUNT(DISTINCT shipments.id) as shipment_count'),
                DB::raw('COUNT(DISTINCT customers.id) as customer_count')
            )
            ->groupBy('branches.id')
            ->get();

        $stats = [
            'total_branches' => DB::table('branches')->count(),
            'active_branches' => DB::table('branches')->where('is_active', true)->count(),
            'total_staff' => DB::table('users')->count(),
            'countries' => DB::table('branches')->distinct('country')->count('country'),
        ];

        return view('settings.branches', compact('branches', 'stats'));
    }

    public function profile()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $user = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('branches', 'users.branch_id', '=', 'branches.id')
            ->select('users.*', 'roles.name as role_name', 'branches.name as branch_name')
            ->where('users.id', session('user_id', 1))
            ->first();

        return view('settings.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        DB::table('users')
            ->where('id', session('user_id', 1))
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'language_preference' => $request->language_preference,
                'updated_at' => now()
            ]);

        return redirect()->route('settings.profile')->with('success', 'Profile updated successfully');
    }

    public function updateSystem(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // In a real application, you would save these to a settings table
        // For demo purposes, we'll just redirect back with a success message

        return redirect()->route('settings.system')->with('success', 'System settings updated successfully');
    }

    public function storeBranch(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        DB::table('branches')->insert([
            'name' => $request->name,
            'code' => $request->code,
            'country' => $request->country,
            'city' => $request->city,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'timezone' => $request->timezone ?? 'Asia/Dubai',
            'currency' => $request->currency ?? 'AED',
            'is_active' => $request->has('is_active'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('settings.branches')->with('success', 'Branch added successfully');
    }

    public function dynamicOptions()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $optionTypes = [
            'service_type' => 'Service Type',
            'transport_mode' => 'Transport Mode',
            'shipment_type' => 'Shipment Type',
            'weight_unit' => 'Weight Unit',
            'volume_unit' => 'Volume Unit',
        ];

        $optionsByType = [];
        foreach ($optionTypes as $type => $label) {
            $optionsByType[$type] = [
                'label' => $label,
                'options' => DB::table('dynamic_options')
                    ->where('option_type', $type)
                    ->orderBy('sort_order')
                    ->orderBy('label')
                    ->get()
            ];
        }

        return view('settings.dynamic-options', compact('optionsByType', 'optionTypes'));
    }

    public function storeDynamicOption(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'option_type' => 'required|string|max:50',
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        DB::table('dynamic_options')->insert([
            'option_type' => $request->option_type,
            'value' => $request->value,
            'label' => $request->label,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('settings.dynamic-options')->with('success', 'Option added successfully');
    }

    public function updateDynamicOption(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        DB::table('dynamic_options')
            ->where('id', $id)
            ->update([
                'value' => $request->value,
                'label' => $request->label,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->has('is_active'),
                'updated_at' => now(),
            ]);

        return redirect()->route('settings.dynamic-options')->with('success', 'Option updated successfully');
    }

    public function deleteDynamicOption($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        DB::table('dynamic_options')->where('id', $id)->delete();

        return redirect()->route('settings.dynamic-options')->with('success', 'Option deleted successfully');
    }
}