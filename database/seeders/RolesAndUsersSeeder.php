<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default roles
        $roles = [
            [
                'name' => 'Super Admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access and control',
                'permissions' => json_encode(['*']),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Manager',
                'display_name' => 'Department Manager',
                'description' => 'Management level access with most permissions',
                'permissions' => json_encode([
                    'view_dashboard', 'view_shipments', 'create_shipments', 'edit_shipments',
                    'view_customers', 'create_customers', 'edit_customers',
                    'view_invoices', 'create_invoices', 'edit_invoices',
                    'view_staff', 'view_finance', 'view_reports'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Accountant',
                'display_name' => 'Accountant',
                'description' => 'Financial operations and reporting access',
                'permissions' => json_encode([
                    'view_dashboard', 'view_invoices', 'create_invoices', 'edit_invoices',
                    'view_finance', 'manage_revenue', 'manage_expenses', 'view_reports'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Operations',
                'display_name' => 'Operations Staff',
                'description' => 'Shipment and customer management access',
                'permissions' => json_encode([
                    'view_dashboard', 'view_shipments', 'create_shipments', 'edit_shipments',
                    'view_customers', 'create_customers', 'edit_customers', 'view_invoices'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'HR Manager',
                'display_name' => 'Human Resources Manager',
                'description' => 'Staff and role management access',
                'permissions' => json_encode([
                    'view_dashboard', 'view_staff', 'create_staff', 'edit_staff',
                    'manage_salaries', 'manage_roles'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Employee',
                'display_name' => 'Regular Employee',
                'description' => 'Basic access to view operations',
                'permissions' => json_encode([
                    'view_dashboard', 'view_shipments', 'view_customers', 'view_invoices'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('roles')->insert($roles);

        // Create demo users
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@globallogistics.com',
                'password' => Hash::make('admin123'),
                'branch_id' => 1,
                'role_id' => 1,
                'phone' => '+971-50-000-0001',
                'language_preference' => 'en',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'John Manager',
                'email' => 'john.manager@globallogistics.com',
                'password' => Hash::make('manager123'),
                'branch_id' => 1,
                'role_id' => 2,
                'phone' => '+971-50-000-0002',
                'language_preference' => 'en',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sarah Accountant',
                'email' => 'sarah.accountant@globallogistics.com',
                'password' => Hash::make('account123'),
                'branch_id' => 1,
                'role_id' => 3,
                'phone' => '+971-50-000-0003',
                'language_preference' => 'en',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mike Operations',
                'email' => 'mike.ops@globallogistics.com',
                'password' => Hash::make('ops123'),
                'branch_id' => 1,
                'role_id' => 4,
                'phone' => '+971-50-000-0004',
                'language_preference' => 'en',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Lisa HR',
                'email' => 'lisa.hr@globallogistics.com',
                'password' => Hash::make('hr123'),
                'branch_id' => 1,
                'role_id' => 5,
                'phone' => '+971-50-000-0005',
                'language_preference' => 'en',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tom Employee',
                'email' => 'tom.employee@globallogistics.com',
                'password' => Hash::make('emp123'),
                'branch_id' => 1,
                'role_id' => 6,
                'phone' => '+971-50-000-0006',
                'language_preference' => 'en',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('users')->insert($users);

        echo "Roles and users seeded successfully!\n";
        echo "Demo login credentials:\n";
        echo "Admin: admin@globallogistics.com / admin123\n";
        echo "Manager: john.manager@globallogistics.com / manager123\n";
        echo "Accountant: sarah.accountant@globallogistics.com / account123\n";
    }
}