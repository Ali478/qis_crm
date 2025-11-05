@extends('layouts.app')

@section('title', 'Edit Employee - Quick International Shipping Company')

@section('page-title', __('Edit Employee'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">{{ __('Edit Employee Information') }} - {{ $employee->first_name }} {{ $employee->last_name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.update', $employee->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Personal Information') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="first_name" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $employee->first_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="last_name" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $employee->last_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="gender" class="form-label">{{ __('Gender') }}</label>
                                    <select name="gender" id="gender" class="form-select">
                                        <option value="">{{ __('Select Gender') }}</option>
                                        <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ $employee->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="birth_date" class="form-label">{{ __('Date of Birth') }}</label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ $employee->birth_date }}">
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
                                    <input type="email" name="email" id="email" class="form-control" value="{{ $employee->email }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" id="phone" class="form-control" value="{{ $employee->phone }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="nationality" class="form-label">{{ __('Nationality') }}</label>
                                    <input type="text" name="nationality" id="nationality" class="form-control" value="{{ $employee->nationality }}">
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">{{ __('Address') }}</label>
                                    <textarea name="address" id="address" class="form-control" rows="2">{{ $employee->address }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="city" class="form-label">{{ __('City') }}</label>
                                    <input type="text" name="city" id="city" class="form-control" value="{{ $employee->city }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="country" class="form-label">{{ __('Country') }}</label>
                                    <select name="country" id="country" class="form-select">
                                        <option value="">{{ __('Select Country') }}</option>
                                        <option value="UAE" {{ $employee->country == 'UAE' ? 'selected' : '' }}>United Arab Emirates</option>
                                        <option value="USA" {{ $employee->country == 'USA' ? 'selected' : '' }}>United States</option>
                                        <option value="UK" {{ $employee->country == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="India" {{ $employee->country == 'India' ? 'selected' : '' }}>India</option>
                                        <option value="China" {{ $employee->country == 'China' ? 'selected' : '' }}>China</option>
                                        <option value="Saudi Arabia" {{ $employee->country == 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
                                        <option value="Egypt" {{ $employee->country == 'Egypt' ? 'selected' : '' }}>Egypt</option>
                                        <option value="Pakistan" {{ $employee->country == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
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
                                    <label for="employee_id" class="form-label">{{ __('Employee ID') }}</label>
                                    <input type="text" class="form-control" value="{{ $employee->employee_id }}" readonly>
                                    <small class="text-muted">{{ __('Employee ID cannot be changed') }}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="branch_id" class="form-label">{{ __('Branch') }}</label>
                                    <select name="branch_id" id="branch_id" class="form-select">
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ $employee->branch_id == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="department" class="form-label">{{ __('Department') }} <span class="text-danger">*</span></label>
                                    <select name="department" id="department" class="form-select" required>
                                        <option value="">{{ __('Select Department') }}</option>
                                        <option value="Operations" {{ $employee->department == 'Operations' ? 'selected' : '' }}>Operations</option>
                                        <option value="Finance" {{ $employee->department == 'Finance' ? 'selected' : '' }}>Finance</option>
                                        <option value="HR" {{ $employee->department == 'HR' ? 'selected' : '' }}>Human Resources</option>
                                        <option value="IT" {{ $employee->department == 'IT' ? 'selected' : '' }}>IT</option>
                                        <option value="Sales" {{ $employee->department == 'Sales' ? 'selected' : '' }}>Sales</option>
                                        <option value="Marketing" {{ $employee->department == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="Customer Service" {{ $employee->department == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                                        <option value="Warehouse" {{ $employee->department == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                                        <option value="Logistics" {{ $employee->department == 'Logistics' ? 'selected' : '' }}>Logistics</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="position" class="form-label">{{ __('Position') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="position" id="position" class="form-control" value="{{ $employee->position }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="employment_type" class="form-label">{{ __('Employment Type') }} <span class="text-danger">*</span></label>
                                    <select name="employment_type" id="employment_type" class="form-select" required>
                                        <option value="">{{ __('Select Type') }}</option>
                                        <option value="full_time" {{ $employee->employment_type == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="part_time" {{ $employee->employment_type == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                        <option value="contract" {{ $employee->employment_type == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="intern" {{ $employee->employment_type == 'intern' ? 'selected' : '' }}>Intern</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="hire_date" class="form-label">{{ __('Hire Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="hire_date" id="hire_date" class="form-control" value="{{ $employee->hire_date }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="on_leave" {{ $employee->status == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                        <option value="terminated" {{ $employee->status == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                    </select>
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
                                        <input type="number" name="basic_salary" id="basic_salary" class="form-control" step="100" value="{{ $employee->basic_salary }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="allowances" class="form-label">{{ __('Allowances') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="allowances" id="allowances" class="form-control" step="50" value="{{ $employee->allowances }}">
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
                                    <input type="text" name="passport_number" id="passport_number" class="form-control" value="{{ $employee->passport_number }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="visa_status" class="form-label">{{ __('Visa Status') }}</label>
                                    <select name="visa_status" id="visa_status" class="form-select">
                                        <option value="">{{ __('Select Status') }}</option>
                                        <option value="Employment Visa" {{ $employee->visa_status == 'Employment Visa' ? 'selected' : '' }}>Employment Visa</option>
                                        <option value="Visit Visa" {{ $employee->visa_status == 'Visit Visa' ? 'selected' : '' }}>Visit Visa</option>
                                        <option value="Residence Visa" {{ $employee->visa_status == 'Residence Visa' ? 'selected' : '' }}>Residence Visa</option>
                                        <option value="Sponsor Transfer" {{ $employee->visa_status == 'Sponsor Transfer' ? 'selected' : '' }}>Sponsor Transfer</option>
                                        <option value="Not Applicable" {{ $employee->visa_status == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="visa_expiry" class="form-label">{{ __('Visa Expiry Date') }}</label>
                                    <input type="date" name="visa_expiry" id="visa_expiry" class="form-control" value="{{ $employee->visa_expiry }}">
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
                                    <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ $employee->bank_name }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="bank_account" class="form-label">{{ __('Account Number') }}</label>
                                    <input type="text" name="bank_account" id="bank_account" class="form-control" value="{{ $employee->bank_account }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="emergency_contact" class="form-label">{{ __('Emergency Contact') }}</label>
                                    <input type="text" name="emergency_contact" id="emergency_contact" class="form-control" value="{{ $employee->emergency_contact }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="emergency_phone" class="form-label">{{ __('Emergency Phone') }}</label>
                                    <input type="tel" name="emergency_phone" id="emergency_phone" class="form-control" value="{{ $employee->emergency_phone }}">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('staff.show', $employee->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="fas fa-redo me-2"></i>{{ __('Reset') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>{{ __('Update Employee') }}
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

    // Calculate on page load
    calculateTotal();

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