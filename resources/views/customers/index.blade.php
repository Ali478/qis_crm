@extends('layouts.app')

@section('title', 'Customers - Quick International Shipping Company')

@section('page-title', __('Customer Management'))

@section('content')
<div class="container-fluid">
    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card glass-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ __('Customer Database') }}</h4>
                        <p class="text-muted mb-0">{{ __('Manage your customer relationships') }}</p>
                    </div>
                    <div>
                        <button class="btn btn-outline-success me-2">
                            <i class="fas fa-file-excel me-2"></i>{{ __('Export') }}
                        </button>
                        <a href="{{ route('customers.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>{{ __('Add Customer') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Cards Grid -->
    <div class="row">
        @foreach($customers as $customer)
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card h-100 customer-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $customer->company_name }}</h5>
                            <span class="badge bg-primary">{{ $customer->customer_code }}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-invoice me-2"></i>Invoices</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="customer-info">
                        <p class="mb-2">
                            <i class="fas fa-user text-primary me-2"></i>
                            <strong>{{ $customer->contact_person }}</strong>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <a href="mailto:{{ $customer->email }}" class="text-decoration-none">{{ $customer->email }}</a>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-phone text-primary me-2"></i>
                            {{ $customer->phone }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            {{ $customer->city }}, {{ $customer->country }}
                        </p>
                    </div>

                    <div class="row mt-3 pt-3 border-top">
                        <div class="col-6">
                            <small class="text-muted d-block">Credit Limit</small>
                            <strong class="h6">${{ number_format($customer->credit_limit, 0) }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Payment Terms</small>
                            <strong class="h6">{{ str_replace('_', ' ', ucfirst($customer->payment_terms)) }}</strong>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-box me-1"></i> {{ $customer->shipment_count ?? 0 }} Shipments
                            </small>
                            @if($customer->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add New Customer') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Company Name') }}</label>
                                <input type="text" name="company_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Customer Code') }}</label>
                                <input type="text" name="customer_code" class="form-control" placeholder="Auto-generated" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Contact Person') }}</label>
                                <input type="text" name="contact_person" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Email') }}</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Phone') }}</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Country') }}</label>
                                <select name="country" class="form-select" required>
                                    <option value="">Select Country</option>
                                    <option value="UAE">United Arab Emirates</option>
                                    <option value="China">China</option>
                                    <option value="Oman">Oman</option>
                                    <option value="India">India</option>
                                    <option value="USA">United States</option>
                                    <option value="UK">United Kingdom</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('City') }}</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Payment Terms') }}</label>
                                <select name="payment_terms" class="form-select">
                                    <option value="cash">Cash</option>
                                    <option value="credit_7">Credit 7 Days</option>
                                    <option value="credit_15">Credit 15 Days</option>
                                    <option value="credit_30">Credit 30 Days</option>
                                    <option value="credit_45">Credit 45 Days</option>
                                    <option value="credit_60">Credit 60 Days</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('Address') }}</label>
                                <textarea name="address" class="form-control" rows="2" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Add Customer') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.customer-card {
    transition: transform 0.3s, box-shadow 0.3s;
    border-radius: 1rem;
    overflow: hidden;
}

.customer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.customer-info p {
    font-size: 0.9rem;
}
</style>
@endsection

@push('scripts')
<script>
// Add search functionality
document.addEventListener('DOMContentLoaded', function() {
    // You can add search and filter functionality here
});
</script>
@endpush