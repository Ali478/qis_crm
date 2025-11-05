@extends('layouts.app')

@section('title', 'Customer Reports - Quick International Shipping Company')

@section('page-title', __('Customer Reports'))

@section('content')
<div class="container-fluid">
    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.customers') }}" class="row g-3">
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

    <!-- Customer Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">{{ number_format($customerStats['total_customers']) }}</div>
                <div class="stat-label">{{ __('Total Customers') }}</div>
                <i class="fas fa-users fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-success">{{ number_format($customerStats['new_customers']) }}</div>
                <div class="stat-label">{{ __('New Customers') }}</div>
                <i class="fas fa-user-plus fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-info">{{ number_format($customerStats['active_customers']) }}</div>
                <div class="stat-label">{{ __('Active Customers') }}</div>
                <i class="fas fa-chart-line fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-warning">{{ $customerStats['top_customers'] }}</div>
                <div class="stat-label">{{ __('VIP Customers') }}</div>
                <i class="fas fa-crown fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Growth Chart -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">{{ __('Customer Growth Trend') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="customerGrowthChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Customer Metrics -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">{{ __('Customer Metrics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-success mb-1">4.2</h4>
                                <small class="text-muted">{{ __('Avg Rating') }}</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info mb-1">{{ number_format(($customerStats['active_customers'] / max($customerStats['total_customers'], 1)) * 100, 1) }}%</h4>
                                <small class="text-muted">{{ __('Retention Rate') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Satisfaction') }}</small>
                            <small class="fw-bold text-success">{{ __('High') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 84%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Loyalty Score') }}</small>
                            <small class="fw-bold text-info">{{ __('Good') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: 76%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Engagement') }}</small>
                            <small class="fw-bold text-warning">{{ __('Average') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: 65%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">{{ __('Top Customers by Shipments') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Rank') }}</th>
                                    <th>{{ __('Company Name') }}</th>
                                    <th>{{ __('Shipments') }}</th>
                                    <th>{{ __('Growth') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $index => $customer)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">#{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $customer->company_name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $customer->shipment_count }}</span>
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            <i class="fas fa-arrow-up"></i> {{ rand(5, 25) }}%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                            {{ $index < 3 ? 'VIP' : 'Regular' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
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
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Customer Growth Chart
    const growthCtx = document.getElementById('customerGrowthChart').getContext('2d');
    const growthData = @json($customerGrowth);

    new Chart(growthCtx, {
        type: 'bar',
        data: {
            labels: growthData.map(item => item.date),
            datasets: [{
                label: 'New Customers',
                data: growthData.map(item => item.count),
                backgroundColor: '#28a745',
                borderColor: '#28a745',
                borderWidth: 1
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
                    beginAtZero: true
                }
            }
        }
    });
});

function exportReport() {
    alert('Exporting customer report to PDF/Excel...');
}
</script>
@endpush