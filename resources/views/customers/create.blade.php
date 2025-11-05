@extends('layouts.app')

@section('title', 'Create Customer - Quick International Shipping Company')

@section('page-title', __('Add New Customer'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Customer Registration Form') }}</h5>
                </div>
                <div class="card-body">
                    @if($errors->any() || session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>{{ __('Error!') }}</strong>
                            @if(session('error'))
                                {{ session('error') }}
                            @else
                                {{ __('Please fix the following errors:') }}
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('customers.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <!-- Customer Code -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Customer Code') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_code" class="form-label">{{ __('Customer Code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_code" id="customer_code" class="form-control" value="{{ old('customer_code', $nextCustomerCode ?? '') }}" required readonly style="background-color: #e9ecef; cursor: not-allowed;">
                                    <small class="form-text text-muted">{{ __('Auto-generated customer code based on latest ID + 1') }}</small>
                                    <div class="invalid-feedback">Please provide a customer code.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Type -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Customer Type') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_type" class="form-label">{{ __('Customer Type') }} <span class="text-danger">*</span></label>
                                    <select name="customer_type" id="customer_type" class="form-select" required>
                                        <option value="company" {{ old('customer_type', 'company') == 'company' ? 'selected' : '' }}>{{ __('Company') }}</option>
                                        <option value="individual" {{ old('customer_type') == 'individual' ? 'selected' : '' }}>{{ __('Individual') }}</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a customer type.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Company Information') }}</h6>
                            </div>
                            <div class="col-md-6" id="company_name_field">
                                <div class="form-group mb-3">
                                    <label for="company_name" class="form-label">{{ __('Company Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name') }}">
                                    <div class="invalid-feedback">Please provide a company name.</div>
                                </div>
                            </div>
                            <div class="col-md-6" id="fullname_field" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="fullname" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="fullname" id="fullname" class="form-control" value="{{ old('fullname') }}">
                                    <div class="invalid-feedback">Please provide a full name.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="contact_person" class="form-label">{{ __('Contact Person') }}</label>
                                    <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ old('contact_person') }}">
                                    <div class="invalid-feedback">Please provide a contact person name.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="representative_name" class="form-label">{{ __('Representative Name') }}</label>
                                    <input type="text" name="representative_name" id="representative_name" class="form-control" value="{{ old('representative_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="representative_number" class="form-label">{{ __('Representative Number') }}</label>
                                    <input type="tel" name="representative_number" id="representative_number" class="form-control" value="{{ old('representative_number') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Contact Details') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                                    <div class="invalid-feedback">Please provide a valid email address.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
                                    <div class="invalid-feedback">Please provide a phone number.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="wechat" class="form-label">{{ __('WeChat') }}</label>
                                    <input type="text" name="wechat" id="wechat" class="form-control" value="{{ old('wechat') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="whatsapp" class="form-label">{{ __('WhatsApp') }}</label>
                                    <input type="text" name="whatsapp" id="whatsapp" class="form-control" value="{{ old('whatsapp') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Address Information') }}</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">{{ __('Street Address') }}</label>
                                    <textarea name="address" id="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="city" class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" required>
                                    <div class="invalid-feedback">Please provide a city.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="country" class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                                    <select name="country" id="country" class="form-select" required>
                                        <option value="">{{ __('Select Country') }}</option>
                                        <option value="UAE" {{ old('country') == 'UAE' ? 'selected' : '' }}>United Arab Emirates</option>
                                        <option value="China" {{ old('country') == 'China' ? 'selected' : '' }}>China</option>
                                        <option value="Oman" {{ old('country') == 'Oman' ? 'selected' : '' }}>Oman</option>
                                        <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                                        <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>United States</option>
                                        <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="Singapore" {{ old('country') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                        <option value="Malaysia" {{ old('country') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                        <option value="Indonesia" {{ old('country') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="Thailand" {{ old('country') == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                        <option value="Japan" {{ old('country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                        <option value="South Korea" {{ old('country') == 'South Korea' ? 'selected' : '' }}>South Korea</option>
                                        <option value="Germany" {{ old('country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                                        <option value="France" {{ old('country') == 'France' ? 'selected' : '' }}>France</option>
                                        <option value="Netherlands" {{ old('country') == 'Netherlands' ? 'selected' : '' }}>Netherlands</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a country.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Terms -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Business Terms') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payment_terms" class="form-label">{{ __('Payment Terms') }}</label>
                                    <select name="payment_terms" id="payment_terms" class="form-select">
                                        <option value="cash" {{ old('payment_terms', 'cash') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="net_15" {{ old('payment_terms') == 'net_15' ? 'selected' : '' }}>Net 15 Days</option>
                                        <option value="net_30" {{ old('payment_terms') == 'net_30' ? 'selected' : '' }}>Net 30 Days</option>
                                        <option value="net_45" {{ old('payment_terms') == 'net_45' ? 'selected' : '' }}>Net 45 Days</option>
                                        <option value="net_60" {{ old('payment_terms') == 'net_60' ? 'selected' : '' }}>Net 60 Days</option>
                                        <option value="prepaid" {{ old('payment_terms') == 'prepaid' ? 'selected' : '' }}>Prepaid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="credit_limit" class="form-label">{{ __('Credit Limit (USD)') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="credit_limit" id="credit_limit" class="form-control" step="1000" value="{{ old('credit_limit', 0) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes" class="form-label">{{ __('Additional Notes') }}</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any special requirements or notes about this customer..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="fas fa-redo me-2"></i>{{ __('Reset') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>{{ __('Create Customer') }}
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

    // Format credit limit input
    document.getElementById('credit_limit').addEventListener('input', function(e) {
        if (e.target.value < 0) e.target.value = 0;
    });

    // Auto-format phone number (optional enhancement)
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9+\-]/g, '');
        e.target.value = value;
    });

    // Handle customer type change - show/hide company name or fullname
    const customerType = document.getElementById('customer_type');
    const companyNameField = document.getElementById('company_name_field');
    const fullnameField = document.getElementById('fullname_field');
    const companyNameInput = document.getElementById('company_name');
    const fullnameInput = document.getElementById('fullname');

    function toggleCustomerTypeFields() {
        if (customerType.value === 'company') {
            companyNameField.style.display = 'block';
            fullnameField.style.display = 'none';
            companyNameInput.required = true;
            fullnameInput.required = false;
            fullnameInput.removeAttribute('required');
            // Don't clear value if it has old input
            if (!fullnameInput.value || fullnameInput.value === '{{ old("fullname") }}') {
                fullnameInput.value = '';
            }
        } else {
            companyNameField.style.display = 'none';
            fullnameField.style.display = 'block';
            companyNameInput.required = false;
            companyNameInput.removeAttribute('required');
            fullnameInput.required = true;
            // Don't clear value if it has old input
            if (!companyNameInput.value || companyNameInput.value === '{{ old("company_name") }}') {
                companyNameInput.value = '';
            }
        }
    }
    
    // Initialize based on old value if exists
    const oldCustomerType = '{{ old("customer_type", "company") }}';
    if (oldCustomerType === 'individual') {
        customerType.value = 'individual';
    }
    
    customerType.addEventListener('change', toggleCustomerTypeFields);
    toggleCustomerTypeFields(); // Initialize on page load
});
</script>
@endpush