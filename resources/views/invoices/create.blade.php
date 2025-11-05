@extends('layouts.app')

@section('title', 'Create Invoice - Quick International Shipping Company')

@section('page-title', __('Create New Invoice'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('New Invoice Form') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="POST" class="needs-validation" id="invoiceForm" novalidate>
                        @csrf

                        <!-- Customer & Shipment Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Invoice Details') }}</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="customer_id" class="form-label">{{ __('Customer') }} <span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-select" required>
                                        <option value="">{{ __('Select Customer') }}</option>
                                        @foreach($customers ?? [] as $customer)
                                            <option value="{{ $customer->id }}"
                                                    data-payment-terms="{{ $customer->payment_terms }}"
                                                    data-credit-limit="{{ $customer->credit_limit }}">
                                                {{ $customer->company_name }} ({{ $customer->customer_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="shipment_id" class="form-label">{{ __('Related Shipment') }}</label>
                                    <select name="shipment_id" id="shipment_id" class="form-select">
                                        <option value="">{{ __('No Shipment') }}</option>
                                        @foreach($shipments ?? [] as $shipment)
                                            <option value="{{ $shipment->id }}"
                                                    data-freight="{{ $shipment->freight_charge }}"
                                                    data-customer="{{ $shipment->customer_id }}">
                                                {{ $shipment->shipment_number }} - {{ $shipment->origin_city }} to {{ $shipment->destination_city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="invoice_date" class="form-label">{{ __('Invoice Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Terms -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="due_date" class="form-label">{{ __('Due Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="due_date" id="due_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('Payment Terms') }}</label>
                                    <input type="text" id="payment_terms_display" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('Credit Limit') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="text" id="credit_limit_display" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Invoice Items') }}</h6>
                                <div class="table-responsive">
                                    <table class="table" id="itemsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 40%">{{ __('Description') }}</th>
                                                <th style="width: 15%">{{ __('Quantity') }}</th>
                                                <th style="width: 20%">{{ __('Unit Price') }}</th>
                                                <th style="width: 20%">{{ __('Amount') }}</th>
                                                <th style="width: 5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-container">
                                            <tr class="item-row">
                                                <td>
                                                    <input type="text" name="items[0][description]" class="form-control" placeholder="Item description" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[0][quantity]" class="form-control quantity" value="1" min="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[0][unit_price]" class="form-control unit-price" step="0.01" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[0][amount]" class="form-control amount" step="0.01" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger remove-item" disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-sm btn-secondary" id="addItem">
                                        <i class="fas fa-plus me-2"></i>{{ __('Add Item') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="row mb-4">
                            <div class="col-md-6 offset-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ __('Subtotal') }}:</span>
                                            <strong>$<span id="subtotal_display">0.00</span></strong>
                                            <input type="hidden" name="subtotal" id="subtotal" value="0">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span>{{ __('Tax') }}:</span>
                                            <div class="input-group" style="width: 150px;">
                                                <input type="number" name="tax_rate" id="tax_rate" class="form-control form-control-sm" value="0" min="0" max="100" step="0.01">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ __('Tax Amount') }}:</span>
                                            <strong>$<span id="tax_amount_display">0.00</span></strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span>{{ __('Discount') }}:</span>
                                            <div class="input-group" style="width: 150px;">
                                                <span class="input-group-text">$</span>
                                                <input type="number" name="discount_amount" id="discount_amount" class="form-control form-control-sm" value="0" min="0" step="0.01">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <h5>{{ __('Total') }}:</h5>
                                            <h5 class="text-primary">$<span id="total_display">0.00</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes" class="form-label">{{ __('Notes / Terms') }}</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Payment terms, special instructions, etc."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                                    </a>
                                    <button type="submit" name="action" value="draft" class="btn btn-outline-primary">
                                        <i class="fas fa-save me-2"></i>{{ __('Save as Draft') }}
                                    </button>
                                    <button type="submit" name="action" value="send" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>{{ __('Create & Send') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;

    // Add new item row
    document.getElementById('addItem').addEventListener('click', function() {
        const tbody = document.getElementById('items-container');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <input type="text" name="items[${itemIndex}][description]" class="form-control" placeholder="Item description" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" value="1" min="1" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][unit_price]" class="form-control unit-price" step="0.01" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][amount]" class="form-control amount" step="0.01" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(newRow);
        itemIndex++;

        // Enable remove button on first row if there's more than one row
        if (tbody.children.length > 1) {
            tbody.querySelector('.remove-item').disabled = false;
        }
    });

    // Remove item row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const tbody = document.getElementById('items-container');
            if (tbody.children.length > 1) {
                e.target.closest('tr').remove();
                calculateTotals();

                // Disable remove button on last remaining row
                if (tbody.children.length === 1) {
                    tbody.querySelector('.remove-item').disabled = true;
                }
            }
        }
    });

    // Calculate line item amount
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('unit-price')) {
            const row = e.target.closest('tr');
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            const amount = quantity * unitPrice;
            row.querySelector('.amount').value = amount.toFixed(2);
            calculateTotals();
        }
    });

    // Calculate totals
    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.amount').forEach(function(input) {
            subtotal += parseFloat(input.value) || 0;
        });

        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const taxAmount = subtotal * (taxRate / 100);
        const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
        const total = subtotal + taxAmount - discount;

        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('subtotal_display').textContent = subtotal.toFixed(2);
        document.getElementById('tax_amount_display').textContent = taxAmount.toFixed(2);
        document.getElementById('total_display').textContent = total.toFixed(2);
    }

    // Recalculate on tax or discount change
    document.getElementById('tax_rate').addEventListener('input', calculateTotals);
    document.getElementById('discount_amount').addEventListener('input', calculateTotals);

    // Customer selection
    document.getElementById('customer_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const paymentTerms = selected.dataset.paymentTerms || '';
        const creditLimit = selected.dataset.creditLimit || '0';

        document.getElementById('payment_terms_display').value = paymentTerms.replace('_', ' ').toUpperCase();
        document.getElementById('credit_limit_display').value = parseFloat(creditLimit).toFixed(2);

        // Calculate due date based on payment terms
        const invoiceDate = document.getElementById('invoice_date').value;
        if (invoiceDate && paymentTerms) {
            const date = new Date(invoiceDate);
            let daysToAdd = 0;

            if (paymentTerms === 'net_15') daysToAdd = 15;
            else if (paymentTerms === 'net_30') daysToAdd = 30;
            else if (paymentTerms === 'net_45') daysToAdd = 45;
            else if (paymentTerms === 'net_60') daysToAdd = 60;

            date.setDate(date.getDate() + daysToAdd);
            document.getElementById('due_date').value = date.toISOString().split('T')[0];
        }
    });

    // Shipment selection - populate freight charge
    document.getElementById('shipment_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const freight = selected.dataset.freight || '0';

        if (freight > 0) {
            // Update first item with freight charge
            const firstRow = document.querySelector('.item-row');
            firstRow.querySelector('input[name*="[description]"]').value = 'Freight Charge - ' + selected.text;
            firstRow.querySelector('.quantity').value = 1;
            firstRow.querySelector('.unit-price').value = freight;
            firstRow.querySelector('.amount').value = freight;
            calculateTotals();
        }
    });

    // Form validation
    const form = document.getElementById('invoiceForm');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endpush