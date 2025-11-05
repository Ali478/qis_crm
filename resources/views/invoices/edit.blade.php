@extends('layouts.app')

@section('title', __('Edit Invoice'))
@section('page-title', __('Edit Invoice'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">{{ __('Edit Invoice') }}: {{ $invoice->invoice_number }}</h2>
                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back') }}
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>{{ __('Invoice Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Customer') }} <span class="text-danger">*</span></label>
                                <select name="customer_id" class="form-select" required>
                                    <option value="">{{ __('Select Customer') }}</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->company_name }} ({{ $customer->customer_code }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Shipment') }}</label>
                                <select name="shipment_id" class="form-select">
                                    <option value="">{{ __('No Shipment') }}</option>
                                    @foreach($shipments as $shipment)
                                    <option value="{{ $shipment->id }}" {{ $invoice->shipment_id == $shipment->id ? 'selected' : '' }}>
                                        {{ $shipment->shipment_number }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Invoice Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="invoice_date" class="form-control" value="{{ $invoice->invoice_date }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Due Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" class="form-control" value="{{ $invoice->due_date }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="draft" {{ $invoice->status == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                                    <option value="sent" {{ $invoice->status == 'sent' ? 'selected' : '' }}>{{ __('Sent') }}</option>
                                    <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                    <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>{{ __('Overdue') }}</option>
                                    <option value="cancelled" {{ $invoice->status == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                </select>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">{{ __('Amount Details') }}</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Subtotal ($)') }} <span class="text-danger">*</span></label>
                                <input type="number" name="subtotal" id="subtotal" class="form-control" step="0.01" value="{{ $invoice->subtotal }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Tax Rate (%)') }}</label>
                                <input type="number" name="tax_rate" id="tax_rate" class="form-control" step="0.01" value="{{ $invoice->tax_rate ?? 0 }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Tax Amount ($)') }}</label>
                                <input type="number" name="tax_amount" id="tax_amount" class="form-control" step="0.01" value="{{ $invoice->tax_amount }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Discount Amount ($)') }}</label>
                                <input type="number" name="discount_amount" id="discount_amount" class="form-control" step="0.01" value="{{ $invoice->discount_amount ?? 0 }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Total Amount ($)') }}</label>
                                <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" value="{{ $invoice->total_amount }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Notes') }}</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $invoice->notes ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>{{ __('Summary') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('Subtotal') }}:</span>
                            <strong id="display_subtotal">${{ number_format($invoice->subtotal, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('Tax') }}:</span>
                            <strong id="display_tax">${{ number_format($invoice->tax_amount, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('Discount') }}:</span>
                            <strong id="display_discount">${{ number_format($invoice->discount_amount ?? 0, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <h6>{{ __('Total') }}:</h6>
                            <h5 class="text-primary" id="display_total">${{ number_format($invoice->total_amount, 2) }}</h5>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('Paid') }}:</span>
                            <strong class="text-success">${{ number_format($invoice->paid_amount ?? 0, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>{{ __('Balance') }}:</span>
                            <strong class="text-danger">${{ number_format($invoice->balance_amount ?? 0, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save me-2"></i>{{ __('Update Invoice') }}
                        </button>
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function calculateTotal() {
        const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const discount = parseFloat(document.getElementById('discount_amount').value) || 0;

        const taxAmount = subtotal * (taxRate / 100);
        const total = subtotal + taxAmount - discount;

        document.getElementById('tax_amount').value = taxAmount.toFixed(2);
        document.getElementById('total_amount').value = total.toFixed(2);

        document.getElementById('display_subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('display_tax').textContent = '$' + taxAmount.toFixed(2);
        document.getElementById('display_discount').textContent = '$' + discount.toFixed(2);
        document.getElementById('display_total').textContent = '$' + total.toFixed(2);
    }

    document.getElementById('subtotal').addEventListener('input', calculateTotal);
    document.getElementById('tax_rate').addEventListener('input', calculateTotal);
    document.getElementById('discount_amount').addEventListener('input', calculateTotal);
</script>
@endpush
@endsection
