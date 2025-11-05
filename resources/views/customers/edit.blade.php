@extends('layouts.app')

@section('title', __('Edit Customer'))
@section('page-title', __('Edit Customer'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">{{ __('Edit Customer') }}: {{ $customer->customer_code }}</h2>
                <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back') }}
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-building me-2"></i>{{ __('Company Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Customer Type') }} <span class="text-danger">*</span></label>
                                <select name="customer_type" id="customer_type" class="form-select" required>
                                    <option value="company" {{ ($customer->customer_type ?? 'company') == 'company' ? 'selected' : '' }}>{{ __('Company') }}</option>
                                    <option value="individual" {{ ($customer->customer_type ?? 'company') == 'individual' ? 'selected' : '' }}>{{ __('Individual') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6" id="company_name_field">
                                <label class="form-label">{{ __('Company Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" id="company_name" class="form-control" value="{{ $customer->company_name ?? '' }}">
                            </div>
                            <div class="col-md-6" id="fullname_field" style="display: none;">
                                <label class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="fullname" id="fullname" class="form-control" value="{{ $customer->fullname ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Contact Person') }}</label>
                                <input type="text" name="contact_person" class="form-control" value="{{ $customer->contact_person ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Representative Name') }}</label>
                                <input type="text" name="representative_name" class="form-control" value="{{ $customer->representative_name ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Representative Number') }}</label>
                                <input type="tel" name="representative_number" class="form-control" value="{{ $customer->representative_number ?? '' }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Phone') }} <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" value="{{ $customer->phone }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('WeChat') }}</label>
                                <input type="text" name="wechat" class="form-control" value="{{ $customer->wechat ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('WhatsApp') }}</label>
                                <input type="text" name="whatsapp" class="form-control" value="{{ $customer->whatsapp ?? '' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Address') }} <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="2" required>{{ $customer->address }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control" value="{{ $customer->city }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                                <input type="text" name="country" class="form-control" value="{{ $customer->country }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>{{ __('Payment Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Payment Terms') }}</label>
                            <select name="payment_terms" class="form-select">
                                <option value="cash" {{ $customer->payment_terms == 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                                <option value="credit_7" {{ $customer->payment_terms == 'credit_7' ? 'selected' : '' }}>{{ __('Credit - 7 Days') }}</option>
                                <option value="credit_15" {{ $customer->payment_terms == 'credit_15' ? 'selected' : '' }}>{{ __('Credit - 15 Days') }}</option>
                                <option value="credit_30" {{ $customer->payment_terms == 'credit_30' ? 'selected' : '' }}>{{ __('Credit - 30 Days') }}</option>
                                <option value="credit_60" {{ $customer->payment_terms == 'credit_60' ? 'selected' : '' }}>{{ __('Credit - 60 Days') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Credit Limit ($)') }}</label>
                            <input type="number" name="credit_limit" class="form-control" step="0.01" value="{{ $customer->credit_limit ?? 0 }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Status') }}</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ $customer->is_active ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="0" {{ !$customer->is_active ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save me-2"></i>{{ __('Update Customer') }}
                        </button>
                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-outline-secondary w-100">
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
document.addEventListener('DOMContentLoaded', function() {
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
        } else {
            companyNameField.style.display = 'none';
            fullnameField.style.display = 'block';
            companyNameInput.required = false;
            fullnameInput.required = true;
        }
    }

    customerType.addEventListener('change', toggleCustomerTypeFields);
    toggleCustomerTypeFields(); // Initialize on page load
});
</script>
@endpush
@endsection
