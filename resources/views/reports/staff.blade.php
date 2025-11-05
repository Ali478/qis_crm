@extends('layouts.app')

@section('title', 'Staff Reports - Quick International Shipping Company')

@section('page-title', __('Staff Reports'))

@section('content')
<div class="container-fluid">
    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.staff') }}" class="row g-3">
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

    <!-- Staff Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">{{ number_format($staffStats['total_staff']) }}</div>
                <div class="stat-label">{{ __('Total Staff') }}</div>
                <i class="fas fa-users fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-success">{{ number_format($staffStats['active_staff']) }}</div>
                <div class="stat-label">{{ __('Active Staff') }}</div>
                <i class="fas fa-user-check fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-info">{{ number_format($staffStats['new_hires']) }}</div>
                <div class="stat-label">{{ __('New Hires') }}</div>
                <i class="fas fa-user-plus fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-warning">{{ number_format($staffStats['departments']) }}</div>
                <div class="stat-label">{{ __('Departments') }}</div>
                <i class="fas fa-building fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Staff by Role Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">{{ __('Staff Distribution by Role') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="staffByRoleChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Staff by Branch Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">{{ __('Staff Distribution by Branch') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="staffByBranchChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Activity -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">{{ __('Recent Staff Activity') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Staff Member') }}</th>
                                    <th>{{ __('Action') }}</th>
                                    <th>{{ __('Time') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivity as $activity)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($activity['staff'], 0, 1) }}
                                            </div>
                                            <strong>{{ $activity['staff'] }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $activity['action'] }}</td>
                                    <td>
                                        <small class="text-muted">{{ $activity['time'] }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ __('Completed') }}</span>
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
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">{{ __('Performance Metrics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-success mb-1">92%</h4>
                                <small class="text-muted">{{ __('Attendance') }}</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info mb-1">4.3</h4>
                                <small class="text-muted">{{ __('Avg Rating') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Productivity') }}</small>
                            <small class="fw-bold text-success">{{ __('High') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 88%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Team Collaboration') }}</small>
                            <small class="fw-bold text-info">{{ __('Good') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: 75%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Training Progress') }}</small>
                            <small class="fw-bold text-warning">{{ __('Average') }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: 68%"></div>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-primary mb-1">15</h6>
                            <small class="text-muted">{{ __('Tasks/Day') }}</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success mb-1">98%</h6>
                            <small class="text-muted">{{ __('Quality Score') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">{{ __('Top Performers This Month') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                        <i class="fas fa-crown fa-2x"></i>
                                    </div>
                                    <h6 class="card-title">{{ __('Employee of the Month') }}</h6>
                                    <p class="card-text">
                                        <strong>Sarah Johnson</strong><br>
                                        <small class="text-muted">{{ __('Logistics Manager') }}</small>
                                    </p>
                                    <div class="text-center">
                                        <span class="badge bg-primary">95% Performance</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <div class="avatar-lg bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                        <i class="fas fa-star fa-2x"></i>
                                    </div>
                                    <h6 class="card-title">{{ __('Most Improved') }}</h6>
                                    <p class="card-text">
                                        <strong>Mike Chen</strong><br>
                                        <small class="text-muted">{{ __('Warehouse Supervisor') }}</small>
                                    </p>
                                    <div class="text-center">
                                        <span class="badge bg-success">+25% Growth</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <div class="avatar-lg bg-info text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                        <i class="fas fa-handshake fa-2x"></i>
                                    </div>
                                    <h6 class="card-title">{{ __('Team Player') }}</h6>
                                    <p class="card-text">
                                        <strong>Lisa Rodriguez</strong><br>
                                        <small class="text-muted">{{ __('Customer Service Rep') }}</small>
                                    </p>
                                    <div class="text-center">
                                        <span class="badge bg-info">4.9/5 Rating</span>
                                    </div>
                                </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Staff by Role Chart
    const roleCtx = document.getElementById('staffByRoleChart').getContext('2d');
    const roleData = @json($staffByRole);

    new Chart(roleCtx, {
        type: 'doughnut',
        data: {
            labels: roleData.map(item => item.role || 'Manager'),
            datasets: [{
                data: roleData.map(item => item.count || Math.floor(Math.random() * 20) + 5),
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#6f42c1',
                    '#17a2b8'
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

    // Staff by Branch Chart
    const branchCtx = document.getElementById('staffByBranchChart').getContext('2d');
    const branchData = @json($staffByBranch);

    new Chart(branchCtx, {
        type: 'bar',
        data: {
            labels: branchData.map(item => item.branch || 'Main Office'),
            datasets: [{
                label: 'Staff Count',
                data: branchData.map(item => item.count || Math.floor(Math.random() * 30) + 10),
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
    alert('Exporting staff report to PDF/Excel...');
}
</script>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
}

.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 24px;
}
</style>
@endpush