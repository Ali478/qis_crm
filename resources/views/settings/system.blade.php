@extends('layouts.app')

@section('title', 'System Settings - Quick International Shipping Company')

@section('page-title', __('System Settings'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('System Configuration') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.system.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Company Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Company Information') }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Company Name') }}</label>
                                <input type="text" class="form-control" name="company_name" value="{{ $settings['company_name'] }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Company Email') }}</label>
                                <input type="email" class="form-control" name="company_email" value="{{ $settings['company_email'] }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Company Phone') }}</label>
                                <input type="text" class="form-control" name="company_phone" value="{{ $settings['company_phone'] }}">
                            </div>
                        </div>

                        <!-- Regional Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('Regional Settings') }}</h6>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('Default Currency') }}</label>
                                <select class="form-select" name="default_currency">
                                    <option value="USD" {{ $settings['default_currency'] == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="AED" {{ $settings['default_currency'] == 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                                    <option value="EUR" {{ $settings['default_currency'] == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ $settings['default_currency'] == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('Default Timezone') }}</label>
                                <select class="form-select" name="default_timezone">
                                    <option value="Asia/Dubai" {{ $settings['default_timezone'] == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai</option>
                                    <option value="UTC" {{ $settings['default_timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="America/New_York" {{ $settings['default_timezone'] == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                    <option value="Europe/London" {{ $settings['default_timezone'] == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('Date Format') }}</label>
                                <select class="form-select" name="date_format">
                                    <option value="Y-m-d" {{ $settings['date_format'] == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                    <option value="m/d/Y" {{ $settings['date_format'] == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                    <option value="d/m/Y" {{ $settings['date_format'] == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                </select>
                            </div>
                        </div>

                        <!-- System Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">{{ __('System Configuration') }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Backup Frequency') }}</label>
                                <select class="form-select" name="backup_frequency">
                                    <option value="daily" {{ $settings['backup_frequency'] == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ $settings['backup_frequency'] == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ $settings['backup_frequency'] == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="maintenance_mode" {{ $settings['maintenance_mode'] ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ __('Maintenance Mode') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>{{ __('Save Settings') }}
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