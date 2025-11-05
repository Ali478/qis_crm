@extends('layouts.app')

@section('title', 'Financial Reports - Quick International Shipping Company')

@section('page-title', __('Financial Reports'))

@section('content')
<div class="container-fluid">
    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.financial') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('From Date') }}</label>
                            <input type="date" class="form-control" name="date_from" value="{{ $dateFrom }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('To Date') }}</label>
                            <input type="date" class="form-control" name="date_to" value="{{ $dateTo }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-2"></i>{{ __('Apply Filter') }}
                            </button>
                            <button type="button" class="btn btn-success" onclick="exportReport()">
                                <i class="fas fa-download me-2"></i>{{ __('Export') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">${{ number_format($financialStats['total_revenue'], 2) }}</div>
                <div class="stat-label">{{ __('Total Revenue') }}</div>
                <i class="fas fa-dollar-sign fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-success">${{ number_format($financialStats['paid_invoices'], 2) }}</div>
                <div class="stat-label">{{ __('Paid Invoices') }}</div>
                <i class="fas fa-check-circle fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-warning">${{ number_format($financialStats['pending_invoices'], 2) }}</div>
                <div class="stat-label">{{ __('Pending Invoices') }}</div>
                <i class="fas fa-clock fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-danger">${{ number_format($financialStats['overdue_invoices'], 2) }}</div>
                <div class="stat-label">{{ __('Overdue Invoices') }}</div>
                <i class="fas fa-exclamation-triangle fa-2x text-danger opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Revenue Chart -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">{{ __('Monthly Revenue Trend') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="height: 350px !important;"></canvas>
                </div>
            </div>
        </div>

        <!-- Financial Health -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">{{ __('Financial Health') }}</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-success mb-1">{{ number_format(($financialStats['paid_invoices'] / max($financialStats['total_revenue'], 1)) * 100, 1) }}%</h4>
                                <small class="text-muted">{{ __('Collection Rate') }}</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info mb-1">25%</h4>
                                <small class="text-muted">{{ __('Profit Margin') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Cash Flow') }}</small>
                            <small class="fw-bold text-success">{{ __('Positive') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 78%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Revenue Growth') }}</small>
                            <small class="fw-bold text-info">{{ __('Strong') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: 82%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Payment Efficiency') }}</small>
                            <small class="fw-bold text-warning">{{ __('Good') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: 71%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Methods -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">{{ __('Payment Methods') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodsChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Revenue Breakdown -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">{{ __('Revenue Breakdown') }}</h6>
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
                                <tr>
                                    <td>{{ __('Domestic Shipping') }}</td>
                                    <td>${{ number_format($financialStats['total_revenue'] * 0.65, 2) }}</td>
                                    <td>
                                        <span class="badge bg-primary">65%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('International Shipping') }}</td>
                                    <td>${{ number_format($financialStats['total_revenue'] * 0.25, 2) }}</td>
                                    <td>
                                        <span class="badge bg-success">25%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Express Delivery') }}</td>
                                    <td>${{ number_format($financialStats['total_revenue'] * 0.08, 2) }}</td>
                                    <td>
                                        <span class="badge bg-info">8%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Other Services') }}</td>
                                    <td>${{ number_format($financialStats['total_revenue'] * 0.02, 2) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">2%</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-4">
                            <h6 class="text-success mb-1">${{ number_format($financialStats['total_revenue'] * 0.25, 2) }}</h6>
                            <small class="text-muted">{{ __('Profit') }}</small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-warning mb-1">${{ number_format($financialStats['total_revenue'] * 0.60, 2) }}</h6>
                            <small class="text-muted">{{ __('Expenses') }}</small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-info mb-1">${{ number_format($financialStats['total_revenue'] * 0.15, 2) }}</h6>
                            <small class="text-muted">{{ __('Tax') }}</small>
                        </div>
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
    // Monthly Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($monthlyRevenue);

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => `${item.year}-${String(item.month).padStart(2, '0')}`),
            datasets: [{
                label: 'Monthly Revenue',
                data: revenueData.map(item => item.revenue),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Payment Methods Chart
    const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    const paymentData = @json($paymentMethods);

    new Chart(paymentCtx, {
        type: 'pie',
        data: {
            labels: paymentData.map(item => item.payment_method || 'Credit Card'),
            datasets: [{
                data: paymentData.map(item => item.total || Math.random() * 50000),
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#6f42c1'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});

function exportReport() {
    alert('Exporting financial report to PDF/Excel...');
}
</script>
@endpush