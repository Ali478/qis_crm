@extends('layouts.app')

@section('title', 'Dashboard - Quick International Shipping Company')

@section('page-title', __('Dashboard'))

@section('content')
<div class="container-fluid">
    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_shipments'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Total Shipments') }}</div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="badge bg-success">Active</span>
                    <i class="fas fa-shipping-fast fa-2x text-primary opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stat-card">
                <div class="stat-value">${{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Total Revenue') }}</div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="badge bg-success">{{ $stats['pending_invoices'] ?? 0 }} pending</span>
                    <i class="fas fa-dollar-sign fa-2x text-success opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_customers'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Active Customers') }}</div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="badge bg-info">Registered</span>
                    <i class="fas fa-users fa-2x text-info opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['in_transit'] ?? 0 }}</div>
                <div class="stat-label">{{ __('In Transit') }}</div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="badge bg-warning">{{ $stats['delivered'] ?? 0 }} delivered</span>
                    <i class="fas fa-clock fa-2x text-warning opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Revenue Overview') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="height: 350px !important;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Shipment Types') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="shipmentTypesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Shipments Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Recent Shipments') }}</h5>
                    <a href="#" class="btn btn-sm btn-primary">{{ __('View All') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Shipment ID') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Origin') }}</th>
                                    <th>{{ __('Destination') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('ETA') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#SH-2024-001247</td>
                                    <td>ABC Trading Co.</td>
                                    <td>Shanghai, CN</td>
                                    <td>Dubai, UAE</td>
                                    <td><span class="badge bg-info">In Transit</span></td>
                                    <td>2024-02-15</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-secondary" title="{{ __('Track') }}">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-success" title="{{ __('Documents') }}">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#SH-2024-001246</td>
                                    <td>XYZ Logistics</td>
                                    <td>Muscat, OM</td>
                                    <td>Shenzhen, CN</td>
                                    <td><span class="badge bg-success">Delivered</span></td>
                                    <td>2024-02-10</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-secondary" title="{{ __('Track') }}">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-success" title="{{ __('Documents') }}">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#SH-2024-001245</td>
                                    <td>Global Imports Ltd</td>
                                    <td>Dubai, UAE</td>
                                    <td>Guangzhou, CN</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>2024-02-20</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-secondary" title="{{ __('Track') }}">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-success" title="{{ __('Documents') }}">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#SH-2024-001244</td>
                                    <td>Fast Freight Co.</td>
                                    <td>Shanghai, CN</td>
                                    <td>Muscat, OM</td>
                                    <td><span class="badge bg-primary">Processing</span></td>
                                    <td>2024-02-18</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-secondary" title="{{ __('Track') }}">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-success" title="{{ __('Documents') }}">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#SH-2024-001243</td>
                                    <td>Ocean Express</td>
                                    <td>Guangzhou, CN</td>
                                    <td>Dubai, UAE</td>
                                    <td><span class="badge bg-danger">Delayed</span></td>
                                    <td>2024-02-12</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-secondary" title="{{ __('Track') }}">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-success" title="{{ __('Documents') }}">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Row -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Quick Actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>{{ __('New Shipment') }}
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>{{ __('Add Customer') }}
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-file-invoice me-2"></i>{{ __('Generate Invoice') }}
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-chart-bar me-2"></i>{{ __('View Reports') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Recent Activities') }}</h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        <div class="activity-item mb-3">
                            <div class="d-flex">
                                <div class="activity-icon bg-success text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="mb-0">Shipment #SH-2024-001246 delivered</p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item mb-3">
                            <div class="d-flex">
                                <div class="activity-icon bg-info text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="mb-0">New customer registered: Tech Imports</p>
                                    <small class="text-muted">5 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item mb-3">
                            <div class="d-flex">
                                <div class="activity-icon bg-warning text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <p class="mb-0">Shipment #SH-2024-001243 delayed</p>
                                    <small class="text-muted">1 day ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="d-flex">
                                <div class="activity-icon bg-primary text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-file"></i>
                                </div>
                                <div>
                                    <p class="mb-0">Invoice #INV-2024-0542 generated</p>
                                    <small class="text-muted">2 days ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Branch Performance') }}</h5>
                </div>
                <div class="card-body">
                    <div class="branch-performance">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Dubai Branch</span>
                                <span>85%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Shanghai Branch</span>
                                <span>92%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary" style="width: 92%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Muscat Branch</span>
                                <span>78%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-warning" style="width: 78%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Guangzhou Branch</span>
                                <span>88%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-info" style="width: 88%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const revenueChart = new Chart(revenueCtx.getContext('2d'), {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Sea Freight',
            data: [45000, 52000, 48000, 58000, 65000, 62000, 68000, 72000, 70000, 75000, 78000, 85000],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }, {
            label: 'Air Freight',
            data: [28000, 32000, 30000, 35000, 38000, 36000, 42000, 45000, 43000, 48000, 50000, 52000],
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: '#10b981',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }, {
            label: 'Land Freight',
            data: [15000, 18000, 17000, 20000, 22000, 21000, 24000, 26000, 25000, 28000, 29000, 31000],
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: '#f59e0b',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true,
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 13
                },
                displayColors: true,
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += '$' + context.parsed.y.toLocaleString();
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                display: true,
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 11
                    }
                }
            },
            y: {
                display: true,
                beginAtZero: false,
                min: 10000,
                max: 100000,
                ticks: {
                    stepSize: 15000,
                    callback: function(value) {
                        return '$' + (value / 1000) + 'k';
                    },
                    font: {
                        size: 11
                    }
                },
                grid: {
                    borderDash: [2, 2],
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            }
        }
    }
        });
    }

    // Shipment Types Pie Chart
    const shipmentCtx = document.getElementById('shipmentTypesChart');
    if (shipmentCtx) {
        const shipmentChart = new Chart(shipmentCtx.getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Sea Freight', 'Air Freight', 'Land Freight', 'Express'],
        datasets: [{
            data: [45, 25, 20, 10],
            backgroundColor: ['#1e40af', '#059669', '#d97706', '#dc2626'],
            borderWidth: 0
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
    }
});
</script>
@endpush