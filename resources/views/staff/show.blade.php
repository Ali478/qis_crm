@extends('layouts.app')

@section('title', 'Employee Profile - Quick International Shipping Company')

@section('page-title', __('Employee Profile'))

@section('content')
<div class="container-fluid">
    <!-- Employee Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name={{ $employee->first_name }}+{{ $employee->last_name }}&background=ffffff&color=1e40af&size=80"
                                 alt="Avatar" class="rounded-circle me-3" width="80" height="80">
                            <div>
                                <h3 class="mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                                <p class="mb-1">
                                    <i class="fas fa-id-badge me-2"></i>{{ $employee->employee_id }}
                                    <span class="ms-3">
                                        <i class="fas fa-briefcase me-2"></i>{{ $employee->position }}
                                    </span>
                                </p>
                                <div>
                                    @if($employee->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($employee->status == 'on_leave')
                                        <span class="badge bg-warning">On Leave</span>
                                    @else
                                        <span class="badge bg-danger">Terminated</span>
                                    @endif
                                    <span class="badge bg-light text-dark ms-2">{{ ucfirst(str_replace('_', ' ', $employee->employment_type)) }}</span>
                                    <span class="badge bg-info ms-2">{{ $employee->department }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('staff.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                            <a href="{{ route('staff.edit', $employee->id) }}" class="btn btn-warning ms-2">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Personal Information -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-user text-primary me-2"></i>{{ __('Personal Information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Full Name') }}</label>
                            <p class="mb-0 fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Gender') }}</label>
                            <p class="mb-0">{{ $employee->gender ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Date of Birth') }}</label>
                            <p class="mb-0">
                                @if($employee->birth_date)
                                    {{ date('M d, Y', strtotime($employee->birth_date)) }}
                                    <small class="text-muted">({{ \Carbon\Carbon::parse($employee->birth_date)->age }} years)</small>
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Nationality') }}</label>
                            <p class="mb-0">{{ $employee->nationality ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">{{ __('Address') }}</label>
                            <p class="mb-0">
                                @if($employee->address)
                                    {{ $employee->address }}<br>
                                    {{ $employee->city }}, {{ $employee->country }}
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-address-card text-primary me-2"></i>{{ __('Contact Information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">{{ __('Email') }}</label>
                        <p class="mb-0">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">{{ __('Phone') }}</label>
                        <p class="mb-0">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <a href="tel:{{ $employee->phone }}">{{ $employee->phone }}</a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">{{ __('Emergency Contact') }}</label>
                        <p class="mb-0">
                            @if($employee->emergency_contact)
                                <strong>{{ $employee->emergency_contact }}</strong><br>
                                <i class="fas fa-phone-alt text-danger me-2"></i>{{ $employee->emergency_phone }}
                            @else
                                Not specified
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Employment Details -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-briefcase text-primary me-2"></i>{{ __('Employment Details') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Employee ID') }}</label>
                            <p class="mb-0 fw-bold">{{ $employee->employee_id }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Branch') }}</label>
                            <p class="mb-0">{{ $employee->branch_name }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Department') }}</label>
                            <p class="mb-0"><span class="badge bg-secondary">{{ $employee->department }}</span></p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Position') }}</label>
                            <p class="mb-0 fw-bold">{{ $employee->position }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Employment Type') }}</label>
                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $employee->employment_type)) }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Hire Date') }}</label>
                            <p class="mb-0">
                                {{ date('M d, Y', strtotime($employee->hire_date)) }}
                                <br>
                                <small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($employee->hire_date)->diffForHumans(null, true) }} of service)
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compensation & Benefits -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-dollar-sign text-primary me-2"></i>{{ __('Compensation & Benefits') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Basic Salary') }}</label>
                            <p class="mb-0 fw-bold text-primary">${{ number_format($employee->basic_salary, 2) }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Allowances') }}</label>
                            <p class="mb-0 text-success">${{ number_format($employee->allowances, 2) }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Total Package') }}</label>
                            <p class="mb-0 fw-bold">${{ number_format($employee->basic_salary + $employee->allowances, 2) }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Annual Salary') }}</label>
                            <p class="mb-0">${{ number_format(($employee->basic_salary + $employee->allowances) * 12, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents & Visa -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-passport text-primary me-2"></i>{{ __('Documents & Visa Information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="text-muted small">{{ __('Passport Number') }}</label>
                            <p class="mb-0">{{ $employee->passport_number ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small">{{ __('Visa Status') }}</label>
                            <p class="mb-0">
                                @if($employee->visa_status)
                                    <span class="badge bg-info">{{ $employee->visa_status }}</span>
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small">{{ __('Visa Expiry') }}</label>
                            <p class="mb-0">
                                @if($employee->visa_expiry)
                                    {{ date('M d, Y', strtotime($employee->visa_expiry)) }}
                                    @php
                                        $daysUntilExpiry = \Carbon\Carbon::parse($employee->visa_expiry)->diffInDays(now());
                                        $isExpiringSoon = $daysUntilExpiry <= 30;
                                    @endphp
                                    @if($isExpiringSoon)
                                        <br><span class="badge bg-warning">Expiring in {{ $daysUntilExpiry }} days</span>
                                    @endif
                                @else
                                    Not applicable
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small">{{ __('Bank Details') }}</label>
                            <p class="mb-0">
                                @if($employee->bank_name)
                                    {{ $employee->bank_name }}<br>
                                    <small class="text-muted">A/C: {{ $employee->bank_account }}</small>
                                @else
                                    Not provided
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-history text-primary me-2"></i>{{ __('Recent Salary History') }}</h6>
                    <a href="{{ route('staff.salaries') }}" class="btn btn-sm btn-primary">{{ __('View All') }}</a>
                </div>
                <div class="card-body">
                    @if(count($salaryHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('Month/Year') }}</th>
                                        <th>{{ __('Basic') }}</th>
                                        <th>{{ __('Allowances') }}</th>
                                        <th>{{ __('Overtime') }}</th>
                                        <th>{{ __('Bonuses') }}</th>
                                        <th>{{ __('Deductions') }}</th>
                                        <th>{{ __('Net Salary') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salaryHistory as $salary)
                                    <tr>
                                        <td><strong>{{ $salary->month }} {{ $salary->year }}</strong></td>
                                        <td>${{ number_format($salary->basic_salary, 2) }}</td>
                                        <td>${{ number_format($salary->allowances, 2) }}</td>
                                        <td>${{ number_format($salary->overtime, 2) }}</td>
                                        <td>${{ number_format($salary->bonuses, 2) }}</td>
                                        <td class="text-danger">-${{ number_format($salary->deductions + $salary->tax, 2) }}</td>
                                        <td class="fw-bold">${{ number_format($salary->net_salary, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $salary->payment_status == 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($salary->payment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-3">{{ __('No salary history available') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush