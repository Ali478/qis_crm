@extends('layouts.app')

@section('title', 'Invoice Details - Quick International Shipping Company')

@section('page-title', __('Invoice') . ' #' . ($invoice->invoice_number ?? ''))

@section('content')
<div class="container-fluid">
    <!-- Invoice Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Invoices') }}
                </a>
                <div class="btn-group">
                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>{{ __('Edit') }}
                    </a>
                    <button class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>{{ __('Print') }}
                    </button>
                    <button class="btn btn-secondary">
                        <i class="fas fa-download me-2"></i>{{ __('Download PDF') }}
                    </button>
                    <button class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i>{{ __('Send to Customer') }}
                    </button>
                    @if($invoice->balance_amount > 0)
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                        <i class="fas fa-dollar-sign me-2"></i>{{ __('Record Payment') }}
                    </button>
                    @endif
                    <button class="btn btn-danger" onclick="confirmDelete({{ $invoice->id }})">
                        <i class="fas fa-trash me-2"></i>{{ __('Delete') }}
                    </button>
                    <form id="delete-form-{{ $invoice->id }}" action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Document -->
    <div class="row">
        <div class="col-12">
            <div class="card invoice-card">
                <div class="card-body p-5">
                    <!-- Invoice Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h2 class="text-primary mb-3">INVOICE</h2>
                            <div class="company-info">
                                <h4>Quick International Shipping Company</h4>
                                <p class="mb-1">123 Business Park, Suite 100</p>
                                <p class="mb-1">Dubai, UAE</p>
                                <p class="mb-1">Phone: +971 4 123 4567</p>
                                <p>Email: info@globallogistics.com</p>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="invoice-details">
                                <table class="table table-borderless table-sm ms-auto" style="max-width: 300px;">
                                    <tr>
                                        <td class="text-muted">Invoice Number:</td>
                                        <td class="fw-bold">{{ $invoice->invoice_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Invoice Date:</td>
                                        <td>{{ date('M d, Y', strtotime($invoice->invoice_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Due Date:</td>
                                        <td>
                                            @php
                                                $isOverdue = strtotime($invoice->due_date) < strtotime(date('Y-m-d')) && $invoice->balance_amount > 0;
                                            @endphp
                                            <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                                {{ date('M d, Y', strtotime($invoice->due_date)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status:</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'draft' => 'secondary',
                                                    'sent' => 'info',
                                                    'viewed' => 'primary',
                                                    'overdue' => 'danger',
                                                    'paid' => 'success',
                                                    'partially_paid' => 'warning',
                                                    'cancelled' => 'dark'
                                                ];
                                                $color = $statusColors[$invoice->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">
                                                {{ str_replace('_', ' ', ucfirst($invoice->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Bill To -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="bill-to">
                                <h6 class="text-muted mb-2">BILL TO:</h6>
                                <h5>{{ $invoice->company_name }}</h5>
                                <p class="mb-1">{{ $invoice->contact_person }}</p>
                                <p class="mb-1">{{ $invoice->customer_address }}</p>
                                <p class="mb-1">{{ $invoice->customer_city }}, {{ $invoice->customer_country }}</p>
                                <p class="mb-1">Phone: {{ $invoice->customer_phone }}</p>
                                <p>Email: {{ $invoice->customer_email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($invoice->shipment_number)
                            <div class="shipment-info">
                                <h6 class="text-muted mb-2">SHIPMENT REFERENCE:</h6>
                                <p class="mb-1">Shipment #: <strong>{{ $invoice->shipment_number }}</strong></p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 45%">{{ __('Description') }}</th>
                                        <th style="width: 15%" class="text-center">{{ __('Quantity') }}</th>
                                        <th style="width: 15%" class="text-end">{{ __('Unit Price') }}</th>
                                        <th style="width: 20%" class="text-end">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($invoice_items) > 0)
                                        @foreach($invoice_items as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-end">${{ number_format($item->amount, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>1</td>
                                            <td>Freight Services</td>
                                            <td class="text-center">1</td>
                                            <td class="text-end">${{ number_format($invoice->subtotal, 2) }}</td>
                                            <td class="text-end">${{ number_format($invoice->subtotal, 2) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="row">
                        <div class="col-md-6">
                            @if($invoice->notes)
                            <div class="notes">
                                <h6 class="text-muted mb-2">{{ __('Notes') }}:</h6>
                                <p>{{ $invoice->notes }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-end">{{ __('Subtotal') }}:</td>
                                    <td class="text-end" style="width: 150px;">${{ number_format($invoice->subtotal, 2) }}</td>
                                </tr>
                                @if($invoice->tax_amount > 0)
                                <tr>
                                    <td class="text-end">{{ __('Tax') }} ({{ $invoice->tax_rate }}%):</td>
                                    <td class="text-end">${{ number_format($invoice->tax_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($invoice->discount_amount > 0)
                                <tr>
                                    <td class="text-end">{{ __('Discount') }}:</td>
                                    <td class="text-end">-${{ number_format($invoice->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td class="text-end"><h5>{{ __('Total') }}:</h5></td>
                                    <td class="text-end"><h5>${{ number_format($invoice->total_amount, 2) }}</h5></td>
                                </tr>
                                @if($invoice->paid_amount > 0)
                                <tr>
                                    <td class="text-end text-success">{{ __('Paid Amount') }}:</td>
                                    <td class="text-end text-success">${{ number_format($invoice->paid_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($invoice->balance_amount > 0)
                                <tr>
                                    <td class="text-end text-danger"><h6>{{ __('Balance Due') }}:</h6></td>
                                    <td class="text-end text-danger"><h6>${{ number_format($invoice->balance_amount, 2) }}</h6></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    @if(count($payments) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-history text-primary me-2"></i>{{ __('Payment History') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Method') }}</th>
                                    <th>{{ __('Reference') }}</th>
                                    <th>{{ __('Notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>{{ date('M d, Y', strtotime($payment->payment_date)) }}</td>
                                    <td class="text-success">${{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td>{{ $payment->reference_number ?: '-' }}</td>
                                    <td>{{ $payment->notes ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Record Payment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Payment Amount') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="amount" class="form-control" step="0.01" max="{{ $invoice->balance_amount }}" required>
                        </div>
                        <small class="text-muted">Maximum: ${{ number_format($invoice->balance_amount, 2) }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Payment Date') }} <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">{{ __('Select Method') }}</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="online">Online Payment</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Reference Number') }}</label>
                        <input type="text" name="reference_number" class="form-control" placeholder="Transaction/Cheque number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>{{ __('Record Payment') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.invoice-card {
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

@media print {
    .header, .sidebar, .btn-group, .card:not(.invoice-card) {
        display: none !important;
    }
    .invoice-card {
        box-shadow: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this invoice? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush