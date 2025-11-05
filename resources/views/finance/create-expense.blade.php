@extends('layouts.app')

@section('title', 'Add Expense - Quick International Shipping Company')

@section('page-title', __('Add New Expense'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">{{ __('Expense Submission Form') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.store-expense') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf

                        <!-- Basic Information ---->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Basic Information') }}</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="category" class="form-label">{{ __('Category') }} <span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-select" required>
                                        <option value="">{{ __('Select Category') }}</option>
                                        <option value="office">{{ __('Office Supplies') }}</option>
                                        <option value="transport">{{ __('Transportation') }}</option>
                                        <option value="utilities">{{ __('Utilities') }}</option>
                                        <option value="maintenance">{{ __('Maintenance') }}</option>
                                        <option value="marketing">{{ __('Marketing') }}</option>
                                        <option value="rent">{{ __('Rent') }}</option>
                                        <option value="salaries">{{ __('Salaries') }}</option>
                                        <option value="other">{{ __('Other') }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ __('Please select a category.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="expense_date" class="form-label">{{ __('Expense Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="expense_date" id="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    <div class="invalid-feedback">
                                        {{ __('Please provide a valid expense date.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="amount" class="form-label">{{ __('Amount') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0.01" required>
                                        <div class="invalid-feedback">
                                            {{ __('Please provide a valid amount.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Expense Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Expense Details') }}</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Brief title of the expense" required>
                                    <div class="invalid-feedback">
                                        {{ __('Please provide a title for the expense.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Detailed description of the expense"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Vendor & Payment Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Vendor & Payment Information') }}</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="vendor_name" class="form-label">{{ __('Vendor Name') }}</label>
                                    <input type="text" name="vendor_name" id="vendor_name" class="form-control" placeholder="Name of the vendor/supplier">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="invoice_number" class="form-label">{{ __('Invoice/Bill Number') }}</label>
                                    <input type="text" name="invoice_number" id="invoice_number" class="form-control" placeholder="Invoice or bill number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="payment_method" class="form-label">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="payment_method" class="form-select" required>
                                        <option value="">{{ __('Select Payment Method') }}</option>
                                        <option value="cash">{{ __('Cash') }}</option>
                                        <option value="credit_card">{{ __('Credit Card') }}</option>
                                        <option value="debit_card">{{ __('Debit Card') }}</option>
                                        <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                                        <option value="cheque">{{ __('Cheque') }}</option>
                                        <option value="online">{{ __('Online Payment') }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ __('Please select a payment method.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Receipt Upload -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Supporting Documents') }}</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="receipt" class="form-label">{{ __('Receipt/Invoice Document') }}</label>
                                    <input type="file" name="receipt" id="receipt" class="form-control" accept="image/*,.pdf,.doc,.docx">
                                    <div class="form-text">
                                        {{ __('Upload receipt, invoice, or any supporting document. Accepted formats: JPG, PNG, PDF, DOC, DOCX (Max: 5MB)') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Branch Selection (if applicable) -->
                        @if(isset($branches) && count($branches) > 1)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Branch Information') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="branch_id" class="form-label">{{ __('Branch') }}</label>
                                    <select name="branch_id" id="branch_id" class="form-select">
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ $branch->id == session('branch_id', 1) ? 'selected' : '' }}>
                                                {{ $branch->branch_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>{{ __('Note:') }}</strong> {{ __('All expense submissions require approval from the Finance Manager before being processed for payment.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('finance.expenses') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="fas fa-redo me-2"></i>{{ __('Reset') }}
                                    </button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-paper-plane me-2"></i>{{ __('Submit Expense') }}
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
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // File upload validation
    document.getElementById('receipt').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                alert('File size must be less than 5MB');
                this.value = '';
                return;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid file format (JPG, PNG, PDF, DOC, DOCX)');
                this.value = '';
                return;
            }
        }
    });

    // Amount formatting
    document.getElementById('amount').addEventListener('blur', function() {
        const value = parseFloat(this.value);
        if (!isNaN(value)) {
            this.value = value.toFixed(2);
        }
    });

    // Category-based suggestions
    document.getElementById('category').addEventListener('change', function() {
        const titleField = document.getElementById('title');
        const suggestions = {
            'office': 'Office Supplies Purchase',
            'transport': 'Transportation Expense',
            'utilities': 'Utility Bill Payment',
            'maintenance': 'Maintenance Service',
            'marketing': 'Marketing Campaign',
            'rent': 'Monthly Rent Payment',
            'salaries': 'Staff Salary Payment',
            'other': 'Miscellaneous Expense'
        };

        if (suggestions[this.value] && !titleField.value) {
            titleField.placeholder = suggestions[this.value];
        }
    });
});
</script>
@endpush