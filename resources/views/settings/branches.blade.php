@extends('layouts.app')

@section('title', 'Branch Management - Quick International Shipping Company')

@section('page-title', __('Branch Management'))

@section('content')
<div class="container-fluid">
    <!-- Branch Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">{{ $stats['total_branches'] }}</div>
                <div class="stat-label">{{ __('Total Branches') }}</div>
                <i class="fas fa-building fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-success">{{ $stats['active_branches'] }}</div>
                <div class="stat-label">{{ __('Active Branches') }}</div>
                <i class="fas fa-check-circle fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-info">{{ $stats['total_staff'] }}</div>
                <div class="stat-label">{{ __('Total Staff') }}</div>
                <i class="fas fa-users fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-warning">{{ $stats['countries'] }}</div>
                <div class="stat-label">{{ __('Countries') }}</div>
                <i class="fas fa-globe fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Branch List') }}</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                    <i class="fas fa-plus me-2"></i>{{ __('Add New Branch') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Branch Cards -->
    <div class="row">
        @foreach($branches as $branch)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 {{ session('current_branch_id') == $branch->id ? 'border-primary border-2' : '' }}">
                <div class="card-header bg-gradient {{ $branch->is_active ? 'bg-success' : 'bg-secondary' }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{ $branch->name }}</h6>
                            <small>Code: {{ $branch->code }}</small>
                        </div>
                        @if(session('current_branch_id') == $branch->id)
                        <span class="badge bg-white text-primary">Current</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-start mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-2 mt-1"></i>
                            <div>
                                <small class="text-muted d-block">{{ __('Location') }}</small>
                                <span>{{ $branch->address }}</span><br>
                                <span>{{ $branch->city }}, {{ $branch->country }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-start mb-2">
                            <i class="fas fa-phone text-success me-2 mt-1"></i>
                            <div>
                                <small class="text-muted d-block">{{ __('Contact') }}</small>
                                <span>{{ $branch->phone }}</span><br>
                                <small>{{ $branch->email }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <h6 class="mb-0">{{ $branch->staff_count ?? 0 }}</h6>
                                <small class="text-muted">{{ __('Staff') }}</small>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-0">{{ $branch->shipment_count ?? 0 }}</h6>
                                <small class="text-muted">{{ __('Shipments') }}</small>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-0">{{ $branch->customer_count ?? 0 }}</h6>
                                <small class="text-muted">{{ __('Customers') }}</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge {{ $branch->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $branch->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="editBranch({{ $branch->id }})" title="{{ __('Edit') }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-info" onclick="viewBranchDetails({{ $branch->id }})" title="{{ __('View Details') }}">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if(!session('current_branch_id') || session('current_branch_id') != $branch->id)
                            <a href="{{ route('switch.branch', $branch->id) }}" class="btn btn-outline-success" title="{{ __('Switch to this branch') }}">
                                <i class="fas fa-exchange-alt"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Add/Edit Branch Modal -->
    <div class="modal fade" id="addBranchModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ __('Add New Branch') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('settings.branches.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Branch Name') }} *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Branch Code') }} *</label>
                                <input type="text" class="form-control" name="code" maxlength="10" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Country') }} *</label>
                                <select class="form-select" name="country" required>
                                    <option value="">{{ __('Select Country') }}</option>
                                    <option value="UAE">United Arab Emirates</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Oman">Oman</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('City') }} *</label>
                                <input type="text" class="form-control" name="city" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Address') }} *</label>
                            <textarea class="form-control" name="address" rows="2" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Phone') }} *</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Email') }} *</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Timezone') }}</label>
                                <select class="form-select" name="timezone">
                                    <option value="Asia/Dubai">Asia/Dubai (GMT+4)</option>
                                    <option value="Asia/Riyadh">Asia/Riyadh (GMT+3)</option>
                                    <option value="Asia/Kuwait">Asia/Kuwait (GMT+3)</option>
                                    <option value="Asia/Qatar">Asia/Qatar (GMT+3)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Currency') }}</label>
                                <select class="form-select" name="currency">
                                    <option value="AED">AED - UAE Dirham</option>
                                    <option value="SAR">SAR - Saudi Riyal</option>
                                    <option value="KWD">KWD - Kuwaiti Dinar</option>
                                    <option value="QAR">QAR - Qatari Riyal</option>
                                    <option value="BHD">BHD - Bahraini Dinar</option>
                                    <option value="OMR">OMR - Omani Rial</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                            <label class="form-check-label" for="isActive">
                                {{ __('Active Branch') }}
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('Save Branch') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editBranch(id) {
    // In a real application, fetch branch data and populate the modal
    alert('Edit branch: ' + id);
}

function viewBranchDetails(id) {
    // In a real application, show detailed branch information
    alert('View details for branch: ' + id);
}
</script>
@endpush