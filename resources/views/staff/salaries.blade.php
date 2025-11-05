@extends('layouts.app')

@section('title', 'Salary Management - Quick International Shipping Company')

@section('page-title', __('Salary Management'))

@section('content')
<div class="container-fluid">
    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value">${{ number_format($stats['total_paid'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Total Paid (YTD)') }}</div>
                <i class="fas fa-money-bill-wave fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['pending_payments'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Pending Payments') }}</div>
                <i class="fas fa-clock fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value">${{ number_format($stats['current_month'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Current Month Total') }}</div>
                <i class="fas fa-calendar-check fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ __('Salary Payments') }}</h4>
                        <p class="text-muted mb-0">{{ __('Manage employee salary payments and payroll') }}</p>
                    </div>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                            <i class="fas fa-calculator me-2"></i>{{ __('Generate Payroll') }}
                        </button>
                        <button class="btn btn-info ms-2">
                            <i class="fas fa-file-export me-2"></i>{{ __('Export') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salaries Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filter Row -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select form-select-sm">
                                <option>{{ __('All Months') }}</option>
                                <option>January</option>
                                <option>February</option>
                                <option>March</option>
                                <option>April</option>
                                <option>May</option>
                                <option>June</option>
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                                <option>October</option>
                                <option>November</option>
                                <option>December</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm">
                                <option>{{ __('Year') }} - {{ date('Y') }}</option>
                                <option>2024</option>
                                <option>2023</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm">
                                <option>{{ __('All Status') }}</option>
                                <option>Paid</option>
                                <option>Pending</option>
                                <option>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" placeholder="{{ __('Search employee...') }}">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Payment ID') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Month/Year') }}</th>
                                    <th>{{ __('Basic Salary') }}</th>
                                    <th>{{ __('Allowances') }}</th>
                                    <th>{{ __('Overtime') }}</th>
                                    <th>{{ __('Bonuses') }}</th>
                                    <th>{{ __('Deductions') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th>{{ __('Net Salary') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salaries as $salary)
                                <tr class="table-row-hover">
                                    <td class="position-relative">
                                        <div>
                                            <strong>{{ $salary->payment_id }}</strong>
                                            <br>
                                            <small class="text-muted">{{ date('M d, Y', strtotime($salary->payment_date)) }}</small>
                                        </div>
                                        <div class="row-actions">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="{{ __('View Payslip') }}">
                                                    <i class="fas fa-file-alt"></i>
                                                </button>
                                                <button class="btn btn-outline-info" title="{{ __('Download') }}">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                @if($salary->payment_status == 'pending')
                                                <button class="btn btn-outline-success" title="{{ __('Mark as Paid') }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ $salary->first_name }}+{{ $salary->last_name }}&background=1e40af&color=fff"
                                                 alt="Avatar" class="rounded-circle me-2" width="30" height="30">
                                            <div>
                                                <strong>{{ $salary->first_name }} {{ $salary->last_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $salary->employee_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $salary->month }}</strong>
                                        <br>
                                        <small>{{ $salary->year }}</small>
                                    </td>
                                    <td>${{ number_format($salary->basic_salary, 2) }}</td>
                                    <td>${{ number_format($salary->allowances, 2) }}</td>
                                    <td>
                                        @if($salary->overtime > 0)
                                            <span class="text-success">+${{ number_format($salary->overtime, 2) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($salary->bonuses > 0)
                                            <span class="text-info">+${{ number_format($salary->bonuses, 2) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($salary->deductions > 0)
                                            <span class="text-danger">-${{ number_format($salary->deductions, 2) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-warning">-${{ number_format($salary->tax, 2) }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">${{ number_format($salary->net_salary, 2) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'paid' => 'success',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger'
                                            ];
                                            $color = $statusColors[$salary->payment_status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($salary->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <i class="fas fa-money-bill fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">{{ __('No salary records found') }}</p>
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

<!-- Generate Payroll Modal -->
<div class="modal fade" id="generatePayrollModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Generate Monthly Payroll') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Select Month') }}</label>
                        <select class="form-select" required>
                            <option value="">{{ __('Choose Month') }}</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Year') }}</label>
                        <input type="number" class="form-control" value="{{ date('Y') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Department') }}</label>
                        <select class="form-select">
                            <option value="">{{ __('All Departments') }}</option>
                            <option value="Operations">Operations</option>
                            <option value="Finance">Finance</option>
                            <option value="HR">Human Resources</option>
                            <option value="IT">IT</option>
                            <option value="Sales">Sales</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('This will generate salary slips for all active employees for the selected period.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calculator me-2"></i>{{ __('Generate Payroll') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filters = document.querySelectorAll('select.form-select-sm, input.form-control-sm');
    filters.forEach(filter => {
        filter.addEventListener('change', function() {
            // Add filter logic here
            console.log('Filter changed');
        });
    });
});
</script>
@endpush