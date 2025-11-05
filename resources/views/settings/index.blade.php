@extends('layouts.app')

@section('title', 'Settings - Quick International Shipping Company')

@section('page-title', __('System Settings'))

@section('content')
<div class="container-fluid">
    <!-- Settings Overview Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">{{ $stats['total_users'] }}</div>
                <div class="stat-label">{{ __('Total Users') }}</div>
                <i class="fas fa-users fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-success">{{ $stats['active_branches'] }}</div>
                <div class="stat-label">{{ __('Active Branches') }}</div>
                <i class="fas fa-building fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-info">{{ $stats['system_uptime'] }}</div>
                <div class="stat-label">{{ __('System Uptime') }}</div>
                <i class="fas fa-server fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-warning">{{ $stats['last_backup'] }}</div>
                <div class="stat-label">{{ __('Last Backup') }}</div>
                <i class="fas fa-database fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Settings Navigation Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">{{ __('Settings Categories') }}</h4>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 card-3d">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-cogs fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">{{ __('System Settings') }}</h5>
                    <p class="card-text text-muted">{{ __('Configure general system settings, currency, timezone, and backup options') }}</p>
                    <a href="{{ route('settings.system') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-right me-2"></i>{{ __('Configure') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 card-3d">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-building fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">{{ __('Branch Management') }}</h5>
                    <p class="card-text text-muted">{{ __('Manage company branches, locations, and regional settings') }}</p>
                    <a href="{{ route('settings.branches') }}" class="btn btn-success">
                        <i class="fas fa-arrow-right me-2"></i>{{ __('Manage') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 card-3d">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title">{{ __('User Profile') }}</h5>
                    <p class="card-text text-muted">{{ __('Update your personal information, preferences, and account settings') }}</p>
                    <a href="{{ route('settings.profile') }}" class="btn btn-info">
                        <i class="fas fa-arrow-right me-2"></i>{{ __('Edit Profile') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 card-3d">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-list-alt fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title">{{ __('Dynamic Options') }}</h5>
                    <p class="card-text text-muted">{{ __('Manage service types, transport modes, shipment types, weight and volume units') }}</p>
                    <a href="{{ route('settings.dynamic-options') }}" class="btn btn-warning">
                        <i class="fas fa-arrow-right me-2"></i>{{ __('Manage Options') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">{{ __('Recent System Activity') }}</h6>
                </div>
                <div class="card-body">
                    @foreach($recentActivity as $activity)
                    <div class="d-flex align-items-center p-2 mb-2 border-bottom">
                        <div class="me-3">
                            <i class="fas fa-clock text-muted"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong>{{ $activity['action'] }}</strong>
                            <br>
                            <small class="text-muted">by {{ $activity['user'] }}</small>
                        </div>
                        <div>
                            <small class="text-muted">{{ $activity['time'] }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">{{ __('Quick Actions') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download me-2"></i>{{ __('Backup System') }}
                        </button>
                        <button class="btn btn-outline-info btn-sm">
                            <i class="fas fa-broom me-2"></i>{{ __('Clear Cache') }}
                        </button>
                        <button class="btn btn-outline-success btn-sm">
                            <i class="fas fa-sync me-2"></i>{{ __('Sync Data') }}
                        </button>
                        <button class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-tools me-2"></i>{{ __('Maintenance Mode') }}
                        </button>
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt me-2"></i>{{ __('Force Logout All') }}
                        </button>
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
    // Add click handlers for quick actions
    document.querySelectorAll('.btn-outline-primary, .btn-outline-info, .btn-outline-success, .btn-outline-warning, .btn-outline-danger').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.textContent.trim();
            console.log('Quick action clicked:', action);
            // In a real application, you would implement these actions
            alert('Action: ' + action + ' - This would be implemented in a real application');
        });
    });
});
</script>
@endpush