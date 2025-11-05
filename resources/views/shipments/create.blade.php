@extends('layouts.app')

@section('title', 'Create Shipment - Quick International Shipping Company')

@section('page-title', __('Create New Shipment'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('New Shipment Form') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('shipments.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <!-- Customer Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Customer Information') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_id" class="form-label">{{ __('Customer') }} <span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-select select2-search" required>
                                        <option value="">{{ __('Select Customer') }}</option>
                                        @foreach($customers ?? [] as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->company_name ?? $customer->fullname }} ({{ $customer->customer_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="service_type" class="form-label">{{ __('Service Type') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="service_type" id="service_type" class="form-select select2-search" required>
                                            <option value="">{{ __('Select Service') }}</option>
                                            @foreach($dynamicOptions['service_type'] ?? [] as $option)
                                                <option value="{{ $option->value }}">{{ $option->label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary" id="addCustomerBtn" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                            <i class="fas fa-plus"></i> {{ __('Add') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Origin & Destination -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Route Information') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <i class="fas fa-map-marker-alt text-primary"></i> {{ __('Origin') }}
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="origin_country" class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                                            <select name="origin_country" id="origin_country" class="form-select select2-search" required>
                                                <option value="">{{ __('Select Country') }}</option>
                                                <option value="UAE">United Arab Emirates</option>
                                                <option value="China">China</option>
                                                <option value="Oman">Oman</option>
                                                <option value="India">India</option>
                                                <option value="USA">United States</option>
                                                <option value="UK">United Kingdom</option>
                                                <option value="Singapore">Singapore</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="origin_city" class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="origin_city" id="origin_city" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="origin_address" class="form-label">{{ __('Address') }}</label>
                                            <textarea name="origin_address" id="origin_address" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <i class="fas fa-map-marker-alt text-success"></i> {{ __('Destination') }}
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="destination_country" class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                                            <select name="destination_country" id="destination_country" class="form-select select2-search" required>
                                                <option value="">{{ __('Select Country') }}</option>
                                                <option value="UAE">United Arab Emirates</option>
                                                <option value="China">China</option>
                                                <option value="Oman">Oman</option>
                                                <option value="India">India</option>
                                                <option value="USA">United States</option>
                                                <option value="UK">United Kingdom</option>
                                                <option value="Singapore">Singapore</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="destination_city" class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="destination_city" id="destination_city" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="destination_address" class="form-label">{{ __('Address') }}</label>
                                            <textarea name="destination_address" id="destination_address" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipment Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Shipment Details') }}</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="transport_mode" class="form-label">{{ __('Transport Mode') }} <span class="text-danger">*</span></label>
                                    <select name="transport_mode" id="transport_mode" class="form-select select2-search" required>
                                        <option value="">{{ __('Select Mode') }}</option>
                                        @foreach($dynamicOptions['transport_mode'] ?? [] as $option)
                                            <option value="{{ $option->value }}">{{ $option->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="shipment_type" class="form-label">{{ __('Shipment Type') }} <span class="text-danger">*</span></label>
                                    <select name="shipment_type" id="shipment_type" class="form-select select2-search" required>
                                        <option value="">{{ __('Select Type') }}</option>
                                        @foreach($dynamicOptions['shipment_type'] ?? [] as $option)
                                            <option value="{{ $option->value }}">{{ $option->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="total_pieces" class="form-label">{{ __('Total Pieces') }}</label>
                                    <input type="number" name="total_pieces" id="total_pieces" class="form-control" min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Weight & Volume -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="total_weight" class="form-label">{{ __('Total Weight') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="total_weight" id="total_weight" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="weight_unit" class="form-label">{{ __('Weight Unit') }}</label>
                                    <select name="weight_unit" id="weight_unit" class="form-select select2-search">
                                        @foreach($dynamicOptions['weight_unit'] ?? [] as $option)
                                            <option value="{{ $option->value }}" {{ $option->value == 'kg' ? 'selected' : '' }}>{{ $option->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="total_volume" class="form-label">{{ __('Total Volume') }}</label>
                                    <input type="number" name="total_volume" id="total_volume" class="form-control" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="volume_unit" class="form-label">{{ __('Volume Unit') }}</label>
                                    <select name="volume_unit" id="volume_unit" class="form-select select2-search">
                                        @foreach($dynamicOptions['volume_unit'] ?? [] as $option)
                                            <option value="{{ $option->value }}" {{ $option->value == 'm3' ? 'selected' : '' }}>{{ $option->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="estimated_arrival" class="form-label">{{ __('Estimated Arrival') }}</label>
                                    <input type="datetime-local" name="estimated_arrival" id="estimated_arrival" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Pricing Information') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="freight_charge" class="form-label">{{ __('Freight Charge ($)') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="freight_charge" id="freight_charge" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="handling_charge" class="form-label">{{ __('Handling Charge ($)') }}</label>
                                    <input type="number" name="handling_charge" id="handling_charge" class="form-control" step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="other_charges" class="form-label">{{ __('Other Charges ($)') }}</label>
                                    <input type="number" name="other_charges" id="other_charges" class="form-control" step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="total_cost" class="form-label">{{ __('Total Cost ($)') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="total_cost" id="total_cost" class="form-control" step="0.01" required readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Special Instructions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="special_instructions" class="form-label">{{ __('Special Instructions') }}</label>
                                    <textarea name="special_instructions" id="special_instructions" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>{{ __('Create Shipment') }}
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

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addCustomerModalLabel">
                    <i class="fas fa-user-plus me-2"></i>{{ __('Add New Customer') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCustomerForm">
                <div class="modal-body">
                    <div id="customerModalError" class="alert alert-danger" style="display: none;"></div>
                    <div id="customerModalSuccess" class="alert alert-success" style="display: none;"></div>

                    <!-- Customer Code -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modal_customer_code" class="form-label">{{ __('Customer Code') }} <span class="text-danger">*</span></label>
                            <input type="text" name="customer_code" id="modal_customer_code" class="form-control" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                            <small class="form-text text-muted">{{ __('Auto-generated') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_customer_type" class="form-label">{{ __('Customer Type') }} <span class="text-danger">*</span></label>
                            <select name="customer_type" id="modal_customer_type" class="form-select" required>
                                <option value="company">{{ __('Company') }}</option>
                                <option value="individual">{{ __('Individual') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Company/Individual Name -->
                    <div class="row mb-3">
                        <div class="col-md-6" id="modal_company_name_field">
                            <label for="modal_company_name" class="form-label">{{ __('Company Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" id="modal_company_name" class="form-control">
                        </div>
                        <div class="col-md-6" id="modal_fullname_field" style="display: none;">
                            <label for="modal_fullname" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="fullname" id="modal_fullname" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="modal_contact_person" class="form-label">{{ __('Contact Person') }}</label>
                            <input type="text" name="contact_person" id="modal_contact_person" class="form-control">
                        </div>
                    </div>

                    <!-- Representative Info -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modal_representative_name" class="form-label">{{ __('Representative Name') }}</label>
                            <input type="text" name="representative_name" id="modal_representative_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="modal_representative_number" class="form-label">{{ __('Representative Number') }}</label>
                            <input type="tel" name="representative_number" id="modal_representative_number" class="form-control">
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modal_email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="modal_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_phone" class="form-label">{{ __('Phone') }} <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="modal_phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modal_wechat" class="form-label">{{ __('WeChat') }}</label>
                            <input type="text" name="wechat" id="modal_wechat" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="modal_whatsapp" class="form-label">{{ __('WhatsApp') }}</label>
                            <input type="text" name="whatsapp" id="modal_whatsapp" class="form-control">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="modal_address" class="form-label">{{ __('Address') }}</label>
                            <textarea name="address" id="modal_address" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modal_city" class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                            <input type="text" name="city" id="modal_city" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_country" class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                            <select name="country" id="modal_country" class="form-select" required>
                                <option value="">{{ __('Select Country') }}</option>
                                <option value="UAE">United Arab Emirates</option>
                                <option value="China">China</option>
                                <option value="Oman">Oman</option>
                                <option value="India">India</option>
                                <option value="USA">United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Japan">Japan</option>
                                <option value="South Korea">South Korea</option>
                                <option value="Germany">Germany</option>
                                <option value="France">France</option>
                                <option value="Netherlands">Netherlands</option>
                            </select>
                        </div>
                    </div>

                    <!-- Payment Terms -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="modal_payment_terms" class="form-label">{{ __('Payment Terms') }}</label>
                            <select name="payment_terms" id="modal_payment_terms" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="net_15">Net 15 Days</option>
                                <option value="net_30">Net 30 Days</option>
                                <option value="net_45">Net 45 Days</option>
                                <option value="net_60">Net 60 Days</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_credit_limit" class="form-label">{{ __('Credit Limit (USD)') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="credit_limit" id="modal_credit_limit" class="form-control" step="1000" value="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ __('Save Customer') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// Initialize Select2 for all searchable dropdowns
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 on all dropdowns with select2-search class
    $('.select2-search').select2({
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder') || 'Select an option';
        }
    });

    // Calculate total cost
    const freightCharge = document.getElementById('freight_charge');
    const handlingCharge = document.getElementById('handling_charge');
    const otherCharges = document.getElementById('other_charges');
    const totalCost = document.getElementById('total_cost');

    function calculateTotal() {
        const freight = parseFloat(freightCharge.value) || 0;
        const handling = parseFloat(handlingCharge.value) || 0;
        const other = parseFloat(otherCharges.value) || 0;
        totalCost.value = (freight + handling + other).toFixed(2);
    }

    freightCharge.addEventListener('input', calculateTotal);
    handlingCharge.addEventListener('input', calculateTotal);
    otherCharges.addEventListener('input', calculateTotal);

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

    // Customer Modal Functionality
    const addCustomerModal = document.getElementById('addCustomerModal');
    const addCustomerForm = document.getElementById('addCustomerForm');
    const modalCustomerType = document.getElementById('modal_customer_type');
    const modalCompanyNameField = document.getElementById('modal_company_name_field');
    const modalFullnameField = document.getElementById('modal_fullname_field');
    const modalCompanyName = document.getElementById('modal_company_name');
    const modalFullname = document.getElementById('modal_fullname');

    // Toggle customer type fields in modal
    function toggleModalCustomerTypeFields() {
        if (modalCustomerType.value === 'company') {
            modalCompanyNameField.style.display = 'block';
            modalFullnameField.style.display = 'none';
            modalCompanyName.required = true;
            modalFullname.required = false;
            modalFullname.value = '';
        } else {
            modalCompanyNameField.style.display = 'none';
            modalFullnameField.style.display = 'block';
            modalCompanyName.required = false;
            modalFullname.required = true;
            modalCompanyName.value = '';
        }
    }

    modalCustomerType.addEventListener('change', toggleModalCustomerTypeFields);

    // When modal opens, fetch next customer code
    addCustomerModal.addEventListener('show.bs.modal', function() {
        // Fetch next customer code via AJAX
        fetch('{{ route("customers.create") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.customer_code) {
                document.getElementById('modal_customer_code').value = data.customer_code;
            } else {
                document.getElementById('modal_customer_code').value = 'AUTO';
            }
        })
        .catch(error => {
            console.error('Error fetching customer code:', error);
            document.getElementById('modal_customer_code').value = 'AUTO';
        });
        
        // Reset form
        addCustomerForm.reset();
        toggleModalCustomerTypeFields();
        document.getElementById('customerModalError').style.display = 'none';
        document.getElementById('customerModalSuccess').style.display = 'none';
    });

    // Handle modal form submission
    addCustomerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const errorDiv = document.getElementById('customerModalError');
        const successDiv = document.getElementById('customerModalSuccess');
        const submitBtn = addCustomerForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        
        // Hide previous messages
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("Saving...") }}';
        
        // Get form data
        const formData = new FormData(addCustomerForm);
        
        // Add CSRF token
        formData.append('_token', '{{ csrf_token() }}');
        
        // Submit via AJAX
        fetch('{{ route("customers.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            // Check if response is ok (status 200-299)
            if (!response.ok) {
                // Try to parse JSON error response
                return response.json().then(data => {
                    throw {
                        message: data.message || 'An error occurred while saving the customer.',
                        errors: data.errors || null,
                        status: response.status
                    };
                }).catch(() => {
                    // If JSON parsing fails, throw generic error
                    throw {
                        message: 'An error occurred while saving the customer. Please try again.',
                        errors: null,
                        status: response.status
                    };
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                successDiv.textContent = data.message || 'Customer added successfully!';
                successDiv.style.display = 'block';
                
                // Add new customer to dropdown
                const customerSelect = document.getElementById('customer_id');
                const newOption = document.createElement('option');
                newOption.value = data.customer.id;
                const displayName = data.customer.company_name || data.customer.fullname;
                newOption.textContent = displayName + ' (' + data.customer.customer_code + ')';
                newOption.selected = true;
                customerSelect.appendChild(newOption);
                
                // Update Select2 dropdown
                $(customerSelect).trigger('change');
                
                // Close modal after 1.5 seconds
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(addCustomerModal);
                    modal.hide();
                    addCustomerForm.reset();
                }, 1500);
            } else {
                throw {
                    message: data.message || 'Failed to add customer',
                    errors: data.errors || null
                };
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Build error message
            let errorMessage = error.message || 'An error occurred while saving the customer. Please try again.';
            
            // Add validation errors if present
            if (error.errors) {
                const errorList = [];
                for (const field in error.errors) {
                    if (error.errors.hasOwnProperty(field)) {
                        errorList.push(...error.errors[field]);
                    }
                }
                if (errorList.length > 0) {
                    errorMessage += '<ul class="mb-0 mt-2"><li>' + errorList.join('</li><li>') + '</li></ul>';
                }
            }
            
            errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i><strong>Error!</strong> ' + errorMessage;
            errorDiv.style.display = 'block';
            
            // Scroll to error message
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });

    // Refresh customer dropdown (if needed)
    function refreshCustomerDropdown() {
        fetch('{{ route("shipments.create") }}')
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newSelect = doc.querySelector('#customer_id');
                if (newSelect) {
                    const currentSelect = document.getElementById('customer_id');
                    currentSelect.innerHTML = newSelect.innerHTML;
                    $(currentSelect).trigger('change');
                }
            })
            .catch(error => {
                console.error('Error refreshing customer dropdown:', error);
            });
    }
});
</script>
@endpush