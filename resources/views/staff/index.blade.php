@extends('layouts.app')

@section('title', 'Staff Management - Quick International Shipping Company')

@section('page-title', __('Staff Management'))

@section('content')
<div class="container-fluid">
    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_staff'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Total Employees') }}</div>
                <i class="fas fa-users fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['active_staff'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Active Staff') }}</div>
                <i class="fas fa-user-check fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['departments'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Departments') }}</div>
                <i class="fas fa-sitemap fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">${{ number_format($stats['total_salary'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Monthly Payroll') }}</div>
                <i class="fas fa-dollar-sign fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ __('Employee Directory') }}</h4>
                        <p class="text-muted mb-0">{{ __('Manage your organization staff') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('staff.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>{{ __('Add Employee') }}
                        </a>
                        <button class="btn btn-info ms-2">
                            <i class="fas fa-download me-2"></i>{{ __('Export') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Position') }}</th>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Hire Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staff as $employee)
                                <tr class="table-row-hover">
                                    <td class="position-relative">
                                        <div>
                                            <strong>{{ $employee->employee_id }}</strong>
                                        </div>
                                        <div class="row-actions">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('staff.show', $employee->id) }}" class="btn btn-outline-primary" title="{{ __('View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-outline-info" title="{{ __('Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-success" title="{{ __('Payslip') }}">
                                                    <i class="fas fa-file-invoice-dollar"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ $employee->first_name }}+{{ $employee->last_name }}&background=1e40af&color=fff"
                                                 alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $employee->employment_type }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($employee->department) }}</span>
                                    </td>
                                    <td>{{ $employee->position }}</td>
                                    <td>{{ $employee->branch_name }}</td>
                                    <td>
                                        <small>
                                            <i class="fas fa-phone text-muted me-1"></i>{{ $employee->phone }}<br>
                                            <i class="fas fa-envelope text-muted me-1"></i>{{ $employee->email }}
                                        </small>
                                    </td>
                                    <td>{{ date('M d, Y', strtotime($employee->hire_date)) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'active' => 'success',
                                                'on_leave' => 'warning',
                                                'terminated' => 'danger'
                                            ];
                                            $color = $statusColors[$employee->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ str_replace('_', ' ', ucfirst($employee->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">{{ __('No employees found') }}</p>
                                        <a href="{{ route('staff.create') }}" class="btn btn-primary">
                                            <i class="fas fa-user-plus me-2"></i>{{ __('Add First Employee') }}
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// DataTable initialization
document.addEventListener('DOMContentLoaded', function() {
    // Add search and filter functionality here if needed
});
</script>
@endpush