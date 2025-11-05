@extends('layouts.app')

@section('title', 'Financial Reports - Quick International Shipping Company')

@section('page-title', __('Financial Reports'))

@section('content')
<div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-success">${{ number_format($summary['total_revenue'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Total Revenue') }}</div>
                <i class="fas fa-arrow-up fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-danger">${{ number_format($summary['total_expenses'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Total Expenses') }}</div>
                <i class="fas fa-arrow-down fa-2x text-danger opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">${{ number_format($summary['net_profit'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Net Profit') }}</div>
                <i class="fas fa-chart-line fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($summary['profit_margin'] ?? 0, 1) }}%</div>
                <div class="stat-label">{{ __('Profit Margin') }}</div>
                <i class="fas fa-percentage fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Report Filters') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ __('Report Type') }}</label>
                            <select class="form-select" id="reportType">
                                <option value="profit_loss">{{ __('Profit & Loss') }}</option>
                                <option value="revenue_analysis">{{ __('Revenue Analysis') }}</option>
                                <option value="expense_breakdown">{{ __('Expense Breakdown') }}</option>
                                <option value="monthly_comparison">{{ __('Monthly Comparison') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ __('Period') }}</label>
                            <select class="form-select" id="period">
                                <option value="current_month">{{ __('Current Month') }}</option>
                                <option value="last_month">{{ __('Last Month') }}</option>
                                <option value="quarter">{{ __('This Quarter') }}</option>
                                <option value="year">{{ __('This Year') }}</option>
                                <option value="custom">{{ __('Custom Range') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">{{ __('From Date') }}</label>
                            <input type="date" class="form-control" id="fromDate" value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">{{ __('To Date') }}</label>
                            <input type="date" class="form-control" id="toDate" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary d-block w-100" id="generateReport">
                                <i class="fas fa-chart-bar me-2"></i>{{ __('Generate') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">{{ __('Revenue vs Expenses Trend') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueExpenseChart" style="height: 350px !important;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">{{ __('Expense Categories') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="expensePieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ __('Monthly Revenue Breakdown') }}</h6>
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download me-1"></i>{{ __('Export') }}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('Source') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Percentage') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revenueBreakdown ?? [] as $revenue)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ ucfirst($revenue['source']) }}</span>
                                    </td>
                                    <td><strong>${{ number_format($revenue['amount'], 2) }}</strong></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" style="width: {{ $revenue['percentage'] }}%">
                                                {{ $revenue['percentage'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ __('Monthly Expense Breakdown') }}</h6>
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download me-1"></i>{{ __('Export') }}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Percentage') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenseBreakdown ?? [] as $expense)
                                <tr>
                                    <td>
                                        <span class="badge bg-danger">{{ ucfirst($expense['category']) }}</span>
                                    </td>
                                    <td><strong>${{ number_format($expense['amount'], 2) }}</strong></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-danger" style="width: {{ $expense['percentage'] }}%">
                                                {{ $expense['percentage'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit & Loss Statement -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Profit & Loss Statement') }}</h5>
                    <div>
                        <button class="btn btn-light btn-sm me-2">
                            <i class="fas fa-print me-1"></i>{{ __('Print') }}
                        </button>
                        <button class="btn btn-light btn-sm">
                            <i class="fas fa-file-pdf me-1"></i>{{ __('PDF') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr class="table-success">
                                    <td><strong>{{ __('REVENUE') }}</strong></td>
                                    <td></td>
                                    <td class="text-end"><strong>${{ number_format($summary['total_revenue'] ?? 0, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">{{ __('Shipment Revenue') }}</td>
                                    <td>${{ number_format($plStatement['shipment_revenue'] ?? 0, 2) }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">{{ __('Service Fees') }}</td>
                                    <td>${{ number_format($plStatement['service_fees'] ?? 0, 2) }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">{{ __('Other Revenue') }}</td>
                                    <td>${{ number_format($plStatement['other_revenue'] ?? 0, 2) }}</td>
                                    <td></td>
                                </tr>
                                <tr class="table-danger">
                                    <td><strong>{{ __('EXPENSES') }}</strong></td>
                                    <td></td>
                                    <td class="text-end"><strong>${{ number_format($summary['total_expenses'] ?? 0, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">{{ __('Operational Expenses') }}</td>
                                    <td>${{ number_format($plStatement['operational_expenses'] ?? 0, 2) }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">{{ __('Staff Salaries') }}</td>
                                    <td>${{ number_format($plStatement['staff_salaries'] ?? 0, 2) }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">{{ __('Administrative Expenses') }}</td>
                                    <td>${{ number_format($plStatement['admin_expenses'] ?? 0, 2) }}</td>
                                    <td></td>
                                </tr>
                                <tr class="table-primary">
                                    <td><strong>{{ __('NET PROFIT/LOSS') }}</strong></td>
                                    <td></td>
                                    <td class="text-end">
                                        <strong class="{{ ($summary['net_profit'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                            ${{ number_format($summary['net_profit'] ?? 0, 2) }}
                                        </strong>
                                    </td>
                                </tr>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue vs Expenses Chart
    const ctx1 = document.getElementById('revenueExpenseChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            datasets: [{
                label: 'Revenue',
                data: [45000, 52000, 48000, 61000, 55000, 67000, 72000, 68000, 75000, 82000],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }, {
                label: 'Expenses',
                data: [32000, 38000, 35000, 42000, 39000, 45000, 48000, 46000, 52000, 55000],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Expense Categories Pie Chart
    const ctx2 = document.getElementById('expensePieChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Transport', 'Rent', 'Utilities', 'Marketing', 'Office', 'Other'],
            datasets: [{
                data: [30, 25, 15, 12, 10, 8],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });

    // Report generation
    document.getElementById('generateReport').addEventListener('click', function() {
        // Add report generation logic here
        console.log('Generating report...');
    });

    // Period change handler
    document.getElementById('period').addEventListener('change', function() {
        if (this.value === 'custom') {
            document.getElementById('fromDate').disabled = false;
            document.getElementById('toDate').disabled = false;
        } else {
            document.getElementById('fromDate').disabled = true;
            document.getElementById('toDate').disabled = true;
        }
    });
});
</script>
@endpush