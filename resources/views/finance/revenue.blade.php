@extends('layouts.app')

@section('title', 'Revenue Management - Quick International Shipping Company')

@section('page-title', __('Revenue Management'))

@section('content')
<div class="container-fluid">
    <!-- Revenue Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value text-success">${{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Total Revenue (YTD)') }}</div>
                <i class="fas fa-chart-line fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">${{ number_format($stats['monthly_revenue'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('This Month') }}</div>
                <i class="fas fa-calendar-check fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['transactions'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Total Transactions') }}</div>
                <i class="fas fa-exchange-alt fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ __('Revenue Transactions') }}</h4>
                        <p class="text-muted mb-0">{{ __('Track all incoming payments and revenue streams') }}</p>
                    </div>
                    <div>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#recordRevenueModal">
                            <i class="fas fa-plus me-2"></i>{{ __('Record Revenue') }}
                        </button>
                        <button class="btn btn-info ms-2">
                            <i class="fas fa-download me-2"></i>{{ __('Export') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filter Row -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select form-select-sm">
                                <option>{{ __('All Sources') }}</option>
                                <option value="shipment">Shipment</option>
                                <option value="invoice">Invoice</option>
                                <option value="service_fee">Service Fee</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm">
                                <option>{{ __('All Customers') }}</option>
                                @foreach($revenue->unique('company_name')->whereNotNull('company_name') as $r)
                                    <option>{{ $r->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control form-control-sm" placeholder="{{ __('From Date') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control form-control-sm" placeholder="{{ __('To Date') }}">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Transaction ID') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Source') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Invoice/Shipment') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenue as $transaction)
                                <tr class="table-row-hover">
                                    <td class="position-relative">
                                        <div>
                                            <strong>{{ $transaction->transaction_id }}</strong>
                                        </div>
                                        <div class="row-actions">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="{{ __('View Details') }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-info" title="{{ __('Download Receipt') }}">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ date('M d, Y', strtotime($transaction->transaction_date)) }}
                                    </td>
                                    <td>
                                        @php
                                            $sourceColors = [
                                                'shipment' => 'primary',
                                                'invoice' => 'success',
                                                'service_fee' => 'info',
                                                'other' => 'secondary'
                                            ];
                                            $color = $sourceColors[$transaction->source] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ str_replace('_', ' ', ucfirst($transaction->source)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($transaction->company_name)
                                            <strong>{{ $transaction->company_name }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $transaction->description }}
                                    </td>
                                    <td>
                                        @if($transaction->invoice_number)
                                            <span class="badge bg-light text-dark">{{ $transaction->invoice_number }}</span>
                                        @elseif($transaction->shipment_id)
                                            <span class="badge bg-light text-dark">SHP-{{ str_pad($transaction->shipment_id, 4, '0', STR_PAD_LEFT) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fas fa-credit-card text-muted me-1"></i>
                                        {{ str_replace('_', ' ', ucfirst($transaction->payment_method)) }}
                                    </td>
                                    <td>
                                        <strong class="text-success">+${{ number_format($transaction->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'completed' => 'success',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger'
                                            ];
                                            $color = $statusColors[$transaction->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-dollar-sign fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">{{ __('No revenue transactions found') }}</p>
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#recordRevenueModal">
                                            <i class="fas fa-plus me-2"></i>{{ __('Record First Revenue') }}
                                        </button>
                                    </td>
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

<!-- Record Revenue Modal -->
<div class="modal fade" id="recordRevenueModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Record New Revenue') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Revenue Source') }} <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="">{{ __('Select Source') }}</option>
                                <option value="shipment">Shipment</option>
                                <option value="invoice">Invoice Payment</option>
                                <option value="service_fee">Service Fee</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Customer') }}</label>
                            <select class="form-select">
                                <option value="">{{ __('Select Customer') }}</option>
                                <option value="1">ABC Trading Co.</option>
                                <option value="2">XYZ Logistics</option>
                                <option value="3">Global Imports Ltd.</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Description') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Brief description of the revenue" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Amount') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Transaction Date') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="">{{ __('Select Method') }}</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="online">Online Payment</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Reference Number') }}</label>
                            <input type="text" class="form-control" placeholder="Transaction reference">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>{{ __('Record Revenue') }}
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
    // Filter functionality
    const filters = document.querySelectorAll('select.form-select-sm, input.form-control-sm');
    filters.forEach(filter => {
        filter.addEventListener('change', function() {
            // Add filter logic here
            console.log('Filter changed');
        });
    });
});
</script>
@endpush