@extends('layouts.app')

@section('title', 'Reports - Quick International Shipping Company')

@section('page-title', __('Reports & Analytics'))

@section('content')
<div class="container-fluid">
    <!-- Reports Overview Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">{{ $stats['total_reports'] }}</div>
                <div class="stat-label">{{ __('Total Reports') }}</div>
                <i class="fas fa-chart-bar fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-success">{{ $stats['generated_today'] }}</div>
                <div class="stat-label">{{ __('Generated Today') }}</div>
                <i class="fas fa-calendar-day fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-info">{{ $stats['scheduled_reports'] }}</div>
                <div class="stat-label">{{ __('Scheduled Reports') }}</div>
                <i class="fas fa-clock fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-warning">{{ $stats['report_storage'] }}</div>
                <div class="stat-label">{{ __('Storage Used') }}</div>
                <i class="fas fa-hdd fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Report Categories -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">{{ __('Report Categories') }}</h4>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card h-100 card-3d">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">{{ __('Shipment Reports') }}</h5>
                    <p class="card-text text-muted">{{ __('Track shipment performance, delivery times, and route analytics') }}</p>
                    <a href="{{ route('reports.shipments') }}" class="btn btn-primary">
                        <i class="fas fa-chart-line me-2"></i>{{ __('View Reports') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card h-100 card-3d">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">{{ __('Customer Reports') }}</h5>
                    <p class="card-text text-muted">{{ __('Analyze customer behavior, growth patterns, and satisfaction metrics') }}</p>
                    <a href="{{ route('reports.customers') }}" class="btn btn-success">
                        <i class="fas fa-chart-pie me-2"></i>{{ __('View Reports') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card h-100 card-3d">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-dollar-sign fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title">{{ __('Financial Reports') }}</h5>
                    <p class="card-text text-muted">{{ __('Revenue analysis, profit margins, and financial performance tracking') }}</p>
                    <a href="{{ route('reports.financial') }}" class="btn btn-info">
                        <i class="fas fa-chart-area me-2"></i>{{ __('View Reports') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card h-100 card-3d">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-tie fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title">{{ __('Staff Reports') }}</h5>
                    <p class="card-text text-muted">{{ __('Employee performance, productivity metrics, and team analytics') }}</p>
                    <a href="{{ route('reports.staff') }}" class="btn btn-warning">
                        <i class="fas fa-users-cog me-2"></i>{{ __('View Reports') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Reports -->
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">{{ __('Recent Reports') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Report Name') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Generated') }}</th>
                                    <th>{{ __('Size') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentReports as $report)
                                <tr class="table-row-hover">
                                    <td class="position-relative">
                                        <div>
                                            <strong>{{ $report['name'] }}</strong>
                                        </div>
                                        <div class="row-actions">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $report['type'] }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $report['generated'] }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $report['size'] }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">{{ __('Quick Actions') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="generateReport('shipments')">
                            <i class="fas fa-plus me-2"></i>{{ __('Generate Shipment Report') }}
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="generateReport('customers')">
                            <i class="fas fa-plus me-2"></i>{{ __('Generate Customer Report') }}
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="generateReport('financial')">
                            <i class="fas fa-plus me-2"></i>{{ __('Generate Financial Report') }}
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="generateReport('staff')">
                            <i class="fas fa-plus me-2"></i>{{ __('Generate Staff Report') }}
                        </button>
                        <hr>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-cog me-2"></i>{{ __('Schedule Report') }}
                        </button>
                        <button class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-download me-2"></i>{{ __('Export All') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Report Insights -->
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">{{ __('Report Insights') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Most Generated') }}</small>
                            <small class="fw-bold">{{ __('Shipment Reports') }}</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 75%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Peak Usage') }}</small>
                            <small class="fw-bold">{{ __('2:00 PM - 4:00 PM') }}</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 60%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Average Size') }}</small>
                            <small class="fw-bold">{{ __('1.2 MB') }}</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: 45%"></div>
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
function generateReport(type) {
    console.log('Generating report for:', type);

    // Show loading state
    const loadingToast = {
        title: 'Generating Report',
        message: `Preparing ${type} report...`,
        type: 'info'
    };

    // In a real application, you would make an AJAX call here
    setTimeout(() => {
        alert(`${type.charAt(0).toUpperCase() + type.slice(1)} report generation started. You will be notified when it's ready.`);
    }, 500);
}

document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to report cards
    document.querySelectorAll('.card-3d').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush