@extends('layouts.app')

@section('title', 'User Profile - Quick International Shipping Company')

@section('page-title', __('User Profile'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3" style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <span class="text-white" style="font-size: 48px; font-weight: bold;">
                            {{ substr(session('user_name', 'User'), 0, 1) }}
                        </span>
                    </div>
                    <h4 class="mb-1">{{ session('user_name', 'User Name') }}</h4>
                    <p class="text-muted mb-3">{{ session('user_role', 'Staff') }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-primary">{{ session('current_branch_name', 'Main Branch') }}</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <hr>
                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted">{{ __('Member Since') }}</small>
                            <p class="mb-0">{{ now()->subMonths(6)->format('F Y') }}</p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">{{ __('Last Login') }}</small>
                            <p class="mb-0">{{ now()->subHours(2)->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">{{ __('Activity Summary') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="mb-0">152</h5>
                            <small class="text-muted">{{ __('Shipments') }}</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">48</h5>
                            <small class="text-muted">{{ __('Customers') }}</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">95</h5>
                            <small class="text-muted">{{ __('Invoices') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Profile Information') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Full Name') }} *</label>
                                <input type="text" class="form-control" name="name" value="{{ session('user_name', 'User Name') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Email Address') }} *</label>
                                <input type="email" class="form-control" name="email" value="{{ session('user_email', 'user@example.com') }}" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Phone Number') }}</label>
                                <input type="tel" class="form-control" name="phone" value="+971-50-123-4567">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Job Title') }}</label>
                                <input type="text" class="form-control" value="{{ session('user_role', 'Logistics Coordinator') }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Department') }}</label>
                                <input type="text" class="form-control" value="Operations" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Employee ID') }}</label>
                                <input type="text" class="form-control" value="EMP-001" readonly>
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-primary mb-3">{{ __('Preferences') }}</h6>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Language Preference') }}</label>
                                <select class="form-select" name="language_preference">
                                    <option value="en">English</option>
                                    <option value="ar">Arabic</option>
                                    <option value="zh">Chinese</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Time Zone') }}</label>
                                <select class="form-select" name="timezone">
                                    <option value="Asia/Dubai">Asia/Dubai (GMT+4)</option>
                                    <option value="Asia/Riyadh">Asia/Riyadh (GMT+3)</option>
                                    <option value="UTC">UTC</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Date Format') }}</label>
                                <select class="form-select" name="date_format">
                                    <option value="Y-m-d">YYYY-MM-DD</option>
                                    <option value="m/d/Y">MM/DD/YYYY</option>
                                    <option value="d/m/Y">DD/MM/YYYY</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Currency Display') }}</label>
                                <select class="form-select" name="currency">
                                    <option value="AED">AED - UAE Dirham</option>
                                    <option value="USD">USD - US Dollar</option>
                                    <option value="EUR">EUR - Euro</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">{{ __('Notification Preferences') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotif" checked>
                                <label class="form-check-label" for="emailNotif">
                                    {{ __('Email Notifications') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sms_notifications" id="smsNotif">
                                <label class="form-check-label" for="smsNotif">
                                    {{ __('SMS Notifications') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="system_alerts" id="systemAlerts" checked>
                                <label class="form-check-label" for="systemAlerts">
                                    {{ __('System Alerts') }}
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">{{ __('Security Settings') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>{{ __('Password') }}</h6>
                            <p class="text-muted">{{ __('Last changed 30 days ago') }}</p>
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="fas fa-key me-2"></i>{{ __('Change Password') }}
                            </button>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __('Two-Factor Authentication') }}</h6>
                            <p class="text-muted">{{ __('Not enabled') }}</p>
                            <button class="btn btn-outline-success btn-sm">
                                <i class="fas fa-shield-alt me-2"></i>{{ __('Enable 2FA') }}
                            </button>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">{{ __('Active Sessions') }}</h6>
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ __('Current Session') }}</h6>
                                    <small class="text-muted">Windows - Chrome - {{ request()->ip() }}</small>
                                </div>
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">{{ __('Change Password') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="#">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Current Password') }}</label>
                        <input type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('New Password') }}</label>
                        <input type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Confirm New Password') }}</label>
                        <input type="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key me-2"></i>{{ __('Update Password') }}
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
    // Handle form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        // Show success message
        alert('Profile updated successfully!');
        // In a real application, this would submit the form via AJAX
    });
});
</script>
@endpush