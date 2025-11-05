@extends('layouts.app')

@section('title', 'Customer Details - Quick International Shipping Company')

@section('page-title', __('Customer Profile'))

@section('content')
<div class="container-fluid">
    <!-- Customer Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-2">{{ $customer->company_name }}</h3>
                            <p class="mb-1">
                                <i class="fas fa-user me-2"></i>{{ $customer->contact_person }}
                                <span class="ms-3">
                                    <i class="fas fa-id-badge me-2"></i>{{ $customer->customer_code }}
                                </span>
                            </p>
                            <div>
                                @if($customer->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                                <span class="badge bg-light text-dark ms-2">{{ $customer->payment_terms }}</span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('customers.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning ms-2">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                            <button class="btn btn-danger ms-2" onclick="confirmDelete({{ $customer->id }})">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                            <form id="delete-form-{{ $customer->id }}" action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="row mb-4">
        <!-- Contact Information -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-address-card text-primary me-2"></i>{{ __('Contact Information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">{{ __('Email') }}</label>
                        <p class="mb-0">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">{{ __('Phone') }}</label>
                        <p class="mb-0">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>
                        </p>
                    </div>
                    <div>
                        <label class="text-muted small">{{ __('Address') }}</label>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            {{ $customer->address }}<br>
                            <span class="ms-4">{{ $customer->city }}, {{ $customer->country }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Information -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-briefcase text-primary me-2"></i>{{ __('Business Information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Payment Terms') }}</label>
                            <p class="mb-0 fw-bold">{{ ucwords(str_replace('_', ' ', $customer->payment_terms)) }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Credit Limit') }}</label>
                            <p class="mb-0 fw-bold">${{ number_format($customer->credit_limit, 2) }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Currency') }}</label>
                            <p class="mb-0 fw-bold">{{ $customer->currency }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Total Shipments') }}</label>
                            <p class="mb-0 fw-bold">{{ count($shipments) }}</p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small">{{ __('Member Since') }}</label>
                            <p class="mb-0">{{ date('M d, Y', strtotime($customer->created_at)) }}</p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small">{{ __('Branch') }}</label>
                            <p class="mb-0">Branch #{{ $customer->branch_id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Shipments -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-shipping-fast text-primary me-2"></i>{{ __('Recent Shipments') }}</h6>
                    <a href="{{ route('shipments.create') }}?customer_id={{ $customer->id }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>New Shipment
                    </a>
                </div>
                <div class="card-body">
                    @if(count($shipments) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Shipment #') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Origin') }}</th>
                                        <th>{{ __('Destination') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shipments as $shipment)
                                    <tr>
                                        <td><strong>{{ $shipment->shipment_number }}</strong></td>
                                        <td>{{ date('M d, Y', strtotime($shipment->created_at)) }}</td>
                                        <td>{{ $shipment->origin_city }}, {{ $shipment->origin_country }}</td>
                                        <td>{{ $shipment->destination_city }}, {{ $shipment->destination_country }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'in_transit' => 'info',
                                                    'delivered' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $color = $statusColors[$shipment->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ ucfirst($shipment->status) }}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('No shipments found for this customer') }}</p>
                            <a href="{{ route('shipments.create') }}?customer_id={{ $customer->id }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create First Shipment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Invoices -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>{{ __('Recent Invoices') }}</h6>
                    <a href="{{ route('invoices.create') }}?customer_id={{ $customer->id }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>New Invoice
                    </a>
                </div>
                <div class="card-body">
                    @if(count($invoices) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Invoice #') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Due Date') }}</th>
                                        <th>{{ __('Total') }}</th>
                                        <th>{{ __('Paid') }}</th>
                                        <th>{{ __('Balance') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <tr>
                                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                        <td>{{ date('M d, Y', strtotime($invoice->invoice_date)) }}</td>
                                        <td>{{ date('M d, Y', strtotime($invoice->due_date)) }}</td>
                                        <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                        <td class="text-success">${{ number_format($invoice->paid_amount, 2) }}</td>
                                        <td class="text-warning">${{ number_format($invoice->balance_amount, 2) }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'draft' => 'secondary',
                                                    'sent' => 'info',
                                                    'paid' => 'success',
                                                    'partially_paid' => 'warning',
                                                    'overdue' => 'danger'
                                                ];
                                                $color = $statusColors[$invoice->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('No invoices found for this customer') }}</p>
                            <a href="{{ route('invoices.create') }}?customer_id={{ $customer->id }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create First Invoice
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush