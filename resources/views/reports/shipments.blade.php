@extends('layouts.app')

@section('title', 'Shipment Reports - Quick International Shipping Company')

@section('page-title', __('Shipment Reports'))

@section('content')
<div class="container-fluid">
    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.shipments') }}" class="row g-3">
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

    <!-- Shipment Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">{{ number_format($shipmentStats['total_shipments']) }}</div>
                <div class="stat-label">{{ __('Total Shipments') }}</div>
                <i class="fas fa-box fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-success">{{ number_format($shipmentStats['delivered']) }}</div>
                <div class="stat-label">{{ __('Delivered') }}</div>
                <i class="fas fa-check-circle fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-info">{{ number_format($shipmentStats['in_transit']) }}</div>
                <div class="stat-label">{{ __('In Transit') }}</div>
                <i class="fas fa-shipping-fast fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-warning">{{ number_format($shipmentStats['pending']) }}</div>
                <div class="stat-label">{{ __('Pending') }}</div>
                <i class="fas fa-clock fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Daily Shipments Chart -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">{{ __('Daily Shipments Trend') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="dailyShipmentsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Shipment Status Distribution -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">{{ __('Status Distribution') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Routes -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">{{ __('Top 10 Routes') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('Origin') }}</th>
                                    <th>{{ __('Destination') }}</th>
                                    <th>{{ __('Count') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shipmentsByRoute as $route)
                                <tr>
                                    <td>{{ $route->origin }}</td>
                                    <td>{{ $route->destination }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $route->count }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">{{ __('Performance Metrics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h4 class="text-success mb-1">{{ number_format(($shipmentStats['delivered'] / max($shipmentStats['total_shipments'], 1)) * 100, 1) }}%</h4>
                                <small class="text-muted">{{ __('Delivery Rate') }}</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-info mb-1">{{ number_format(($shipmentStats['in_transit'] / max($shipmentStats['total_shipments'], 1)) * 100, 1) }}%</h4>
                            <small class="text-muted">{{ __('In Transit Rate') }}</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">3.2</h4>
                                <small class="text-muted">{{ __('Avg Days') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">98.5%</h4>
                            <small class="text-muted">{{ __('On-Time Rate') }}</small>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Delivery Performance') }}</small>
                            <small class="fw-bold text-success">{{ __('Excellent') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 85%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Route Efficiency') }}</small>
                            <small class="fw-bold text-info">{{ __('Good') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: 72%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Customer Satisfaction') }}</small>
                            <small class="fw-bold text-warning">{{ __('Average') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: 68%"></div>
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
    // Daily Shipments Chart
    const dailyCtx = document.getElementById('dailyShipmentsChart').getContext('2d');
    const dailyData = @json($dailyShipments);

    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyData.map(item => item.date),
            datasets: [{
                label: 'Daily Shipments',
                data: dailyData.map(item => item.count),
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
                    beginAtZero: true
                }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = [
        {{ $shipmentStats['delivered'] }},
        {{ $shipmentStats['in_transit'] }},
        {{ $shipmentStats['pending'] }}
    ];

    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Delivered', 'In Transit', 'Pending'],
            datasets: [{
                data: statusData,
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107'
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
    // In a real application, this would trigger a download
    alert('Exporting shipment report to PDF/Excel...');
}
</script>
@endpush