@extends('layouts.app')

@section('title', 'Roles & Permissions - Quick International Shipping Company')

@section('page-title', __('Roles & Permissions'))

@section('content')
<div class="container-fluid">
    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ count($roles) }}</div>
                <div class="stat-label">{{ __('Total Roles') }}</div>
                <i class="fas fa-user-tag fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ count($roleAssignments) }}</div>
                <div class="stat-label">{{ __('Users with Roles') }}</div>
                <i class="fas fa-users fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ array_sum(array_map('count', $permissions)) }}</div>
                <div class="stat-label">{{ __('Total Permissions') }}</div>
                <i class="fas fa-shield-alt fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ count($permissions) }}</div>
                <div class="stat-label">{{ __('Permission Groups') }}</div>
                <i class="fas fa-layer-group fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ __('Role Management') }}</h4>
                        <p class="text-muted mb-0">{{ __('Manage user roles and system permissions') }}</p>
                    </div>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                            <i class="fas fa-plus me-2"></i>{{ __('Create Role') }}
                        </button>
                        <button class="btn btn-info ms-2" data-bs-toggle="modal" data-bs-target="#assignRoleModal">
                            <i class="fas fa-user-plus me-2"></i>{{ __('Assign Role') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Roles List -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('System Roles') }}</h5>
                </div>
                <div class="card-body">
                    @forelse($roles as $role)
                    <div class="d-flex justify-content-between align-items-center p-3 mb-2 border rounded">
                        <div>
                            <h6 class="mb-1">{{ $role->name }}</h6>
                            <p class="text-muted mb-0 small">{{ $role->description ?? __('No description provided') }}</p>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" title="{{ __('Edit Role') }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-info" title="{{ __('View Permissions') }}"
                                    onclick="viewRolePermissions('{{ $role->name }}', '{{ $role->permissions ?? '' }}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($role->name != 'Super Admin')
                            <button class="btn btn-outline-danger" title="{{ __('Delete Role') }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted py-3">{{ __('No roles found') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Permission Matrix -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">{{ __('Permission Matrix') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Module') }}</th>
                                    <th>{{ __('Permissions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $module => $perms)
                                <tr>
                                    <td><strong>{{ ucfirst($module) }}</strong></td>
                                    <td>
                                        @foreach($perms as $perm)
                                        <span class="badge bg-light text-dark me-1 mb-1">{{ str_replace('_', ' ', $perm) }}</span>
                                        @endforeach
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

    <!-- User Role Assignments -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">{{ __('User Role Assignments') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Current Role') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roleAssignments as $assignment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ $assignment->name }}&background=1e40af&color=fff"
                                                 alt="Avatar" class="rounded-circle me-2" width="30" height="30">
                                            <strong>{{ $assignment->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $assignment->email }}</td>
                                    <td>
                                        @if($assignment->role_name)
                                        <span class="badge bg-primary">{{ $assignment->role_name }}</span>
                                        @else
                                        <span class="badge bg-secondary">{{ __('No Role Assigned') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-warning" title="{{ __('Change Role') }}"
                                                    onclick="changeUserRole({{ $assignment->id }}, '{{ $assignment->name }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-info" title="{{ __('View Permissions') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">{{ __('No users found') }}</td>
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

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New Role') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Role Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="e.g., Manager, Accountant" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Display Name') }}</label>
                            <input type="text" class="form-control" placeholder="Display name for UI">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control" rows="2" placeholder="Brief description of this role"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">{{ __('Permissions') }}</label>
                            <div class="row">
                                @foreach($permissions as $module => $perms)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light py-2">
                                            <h6 class="mb-0">{{ ucfirst($module) }}</h6>
                                        </div>
                                        <div class="card-body py-2">
                                            @foreach($perms as $perm)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $perm }}" id="perm_{{ $perm }}">
                                                <label class="form-check-label" for="perm_{{ $perm }}">
                                                    {{ str_replace('_', ' ', ucfirst($perm)) }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ __('Create Role') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Role Modal -->
<div class="modal fade" id="assignRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Assign Role to User') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Select User') }} <span class="text-danger">*</span></label>
                        <select class="form-select" required>
                            <option value="">{{ __('Choose User') }}</option>
                            @foreach($roleAssignments as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Select Role') }} <span class="text-danger">*</span></label>
                        <select class="form-select" required>
                            <option value="">{{ __('Choose Role') }}</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-user-plus me-2"></i>{{ __('Assign Role') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Role Modal -->
<div class="modal fade" id="changeRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Change User Role') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('User') }}</label>
                        <input type="text" class="form-control" id="changeRoleUserName" readonly>
                        <input type="hidden" id="changeRoleUserId">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('New Role') }} <span class="text-danger">*</span></label>
                        <select class="form-select" required>
                            <option value="">{{ __('Choose Role') }}</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-exchange-alt me-2"></i>{{ __('Change Role') }}
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
    // Functions for role management
    window.viewRolePermissions = function(roleName, permissions) {
        alert('Role: ' + roleName + '\nPermissions: ' + (permissions || 'None assigned'));
    };

    window.changeUserRole = function(userId, userName) {
        document.getElementById('changeRoleUserId').value = userId;
        document.getElementById('changeRoleUserName').value = userName;

        const modal = new bootstrap.Modal(document.getElementById('changeRoleModal'));
        modal.show();
    };

    // Select all checkboxes in a module
    document.querySelectorAll('.card-header').forEach(header => {
        header.addEventListener('click', function() {
            const card = this.closest('.card');
            const checkboxes = card.querySelectorAll('input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(cb => cb.checked = !allChecked);
        });
    });
});
</script>
@endpush