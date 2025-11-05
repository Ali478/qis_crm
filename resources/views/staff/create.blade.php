@extends('layouts.app')

@section('title', 'Add Employee - Quick International Shipping Company')

@section('page-title', __('Add New Employee'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Employee Registration Form') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Personal Information') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="first_name" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="last_name" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="gender" class="form-label">{{ __('Gender') }}</label>
                                    <select name="gender" id="gender" class="form-select">
                                        <option value="">{{ __('Select Gender') }}</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="birth_date" class="form-label">{{ __('Date of Birth') }}</label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Contact Information') }}</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" id="phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="nationality" class="form-label">{{ __('Nationality') }}</label>
                                    <input type="text" name="nationality" id="nationality" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">{{ __('Address') }}</label>
                                    <textarea name="address" id="address" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="city" class="form-label">{{ __('City') }}</label>
                                    <input type="text" name="city" id="city" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="country" class="form-label">{{ __('Country') }}</label>
                                    <select name="country" id="country" class="form-select">
                                        <option value="">{{ __('Select Country') }}</option>
                                        <option value="UAE">United Arab Emirates</option>
                                        <option value="USA">United States</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="India">India</option>
                                        <option value="China">China</option>
                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                        <option value="Egypt">Egypt</option>
                                        <option value="Pakistan">Pakistan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Employment Details') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="branch_id" class="form-label">{{ __('Branch') }}</label>
                                    <select name="branch_id" id="branch_id" class="form-select">
                                        <option value="1">Main Branch - Dubai</option>
                                        <option value="2">Abu Dhabi Branch</option>
                                        <option value="3">Sharjah Branch</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="department" class="form-label">{{ __('Department') }} <span class="text-danger">*</span></label>
                                    <select name="department" id="department" class="form-select" required>
                                        <option value="">{{ __('Select Department') }}</option>
                                        <option value="Operations">Operations</option>
                                        <option value="Finance">Finance</option>
                                        <option value="HR">Human Resources</option>
                                        <option value="IT">IT</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Customer Service">Customer Service</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="Logistics">Logistics</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="position" class="form-label">{{ __('Position') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="position" id="position" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="employment_type" class="form-label">{{ __('Employment Type') }} <span class="text-danger">*</span></label>
                                    <select name="employment_type" id="employment_type" class="form-select" required>
                                        <option value="">{{ __('Select Type') }}</option>
                                        <option value="full_time">Full Time</option>
                                        <option value="part_time">Part Time</option>
                                        <option value="contract">Contract</option>
                                        <option value="intern">Intern</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="hire_date" class="form-label">{{ __('Hire Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="hire_date" id="hire_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Compensation -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Compensation') }}</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="basic_salary" class="form-label">{{ __('Basic Salary (Monthly)') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="basic_salary" id="basic_salary" class="form-control" step="100" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="allowances" class="form-label">{{ __('Allowances') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="allowances" id="allowances" class="form-control" step="50" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="total_package" class="form-label">{{ __('Total Package') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" id="total_package" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents & Visa -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Documents & Visa') }}</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="passport_number" class="form-label">{{ __('Passport Number') }}</label>
                                    <input type="text" name="passport_number" id="passport_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="visa_status" class="form-label">{{ __('Visa Status') }}</label>
                                    <select name="visa_status" id="visa_status" class="form-select">
                                        <option value="">{{ __('Select Status') }}</option>
                                        <option value="Employment Visa">Employment Visa</option>
                                        <option value="Visit Visa">Visit Visa</option>
                                        <option value="Residence Visa">Residence Visa</option>
                                        <option value="Sponsor Transfer">Sponsor Transfer</option>
                                        <option value="Not Applicable">Not Applicable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="visa_expiry" class="form-label">{{ __('Visa Expiry Date') }}</label>
                                    <input type="date" name="visa_expiry" id="visa_expiry" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Banking & Emergency -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Banking & Emergency Contact') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="bank_name" class="form-label">{{ __('Bank Name') }}</label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="bank_account" class="form-label">{{ __('Account Number') }}</label>
                                    <input type="text" name="bank_account" id="bank_account" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="emergency_contact" class="form-label">{{ __('Emergency Contact') }}</label>
                                    <input type="text" name="emergency_contact" id="emergency_contact" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="emergency_phone" class="form-label">{{ __('Emergency Phone') }}</label>
                                    <input type="tel" name="emergency_phone" id="emergency_phone" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="fas fa-redo me-2"></i>{{ __('Reset') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>{{ __('Add Employee') }}
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
    // Calculate total package
    function calculateTotal() {
        const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
        const allowances = parseFloat(document.getElementById('allowances').value) || 0;
        document.getElementById('total_package').value = (basicSalary + allowances).toFixed(2);
    }

    document.getElementById('basic_salary').addEventListener('input', calculateTotal);
    document.getElementById('allowances').addEventListener('input', calculateTotal);

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
});
</script>
@endpush