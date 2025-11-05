@extends('layouts.app')

@section('title', 'Expense Management - Quick International Shipping Company')

@section('page-title', __('Expense Management'))

@section('content')
<div class="container-fluid">
    <!-- Expense Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value text-danger">${{ number_format($stats['total_expenses'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Total Expenses (YTD)') }}</div>
                <i class="fas fa-chart-pie fa-2x text-danger opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value text-warning">${{ number_format($stats['pending_approval'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Pending Approval') }}</div>
                <i class="fas fa-clock fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">${{ number_format($stats['monthly_expenses'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('This Month') }}</div>
                <i class="fas fa-calendar-day fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ __('Company Expenses') }}</h4>
                        <p class="text-muted mb-0">{{ __('Track and manage all business expenses') }}</p>
                    </div>
                    <div>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                            <i class="fas fa-plus me-2"></i>{{ __('Add Expense') }}
                        </button>
                        <button class="btn btn-info ms-2">
                            <i class="fas fa-download me-2"></i>{{ __('Export') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filter Row -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select form-select-sm">
                                <option>{{ __('All Categories') }}</option>
                                <option value="office">Office Supplies</option>
                                <option value="transport">Transportation</option>
                                <option value="utilities">Utilities</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="marketing">Marketing</option>
                                <option value="rent">Rent</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm">
                                <option>{{ __('All Status') }}</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="paid">Paid</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control form-control-sm" placeholder="{{ __('From Date') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control form-control-sm" placeholder="{{ __('To Date') }}">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Expense No.') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Vendor') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                <tr class="table-row-hover">
                                    <td class="position-relative">
                                        <div>
                                            <strong>{{ $expense->expense_number }}</strong>
                                        </div>
                                        <div class="row-actions">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="{{ __('View Details') }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($expense->status == 'pending')
                                                <button class="btn btn-outline-success" title="{{ __('Approve') }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" title="{{ __('Reject') }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                @endif
                                                @if($expense->receipt_path)
                                                <button class="btn btn-outline-info" title="{{ __('View Receipt') }}">
                                                    <i class="fas fa-receipt"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ date('M d, Y', strtotime($expense->expense_date)) }}
                                    </td>
                                    <td>
                                        @php
                                            $categoryColors = [
                                                'office' => 'primary',
                                                'transport' => 'info',
                                                'utilities' => 'warning',
                                                'maintenance' => 'dark',
                                                'marketing' => 'success',
                                                'rent' => 'secondary',
                                                'other' => 'light'
                                            ];
                                            $color = $categoryColors[$expense->category] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ str_replace('_', ' ', ucfirst($expense->category)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $expense->title }}</strong>
                                        @if($expense->description)
                                            <br><small class="text-muted">{{ Str::limit($expense->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $expense->vendor_name ?? '-' }}
                                        @if($expense->invoice_number)
                                            <br><small class="text-muted">Inv: {{ $expense->invoice_number }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-danger">${{ number_format($expense->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-credit-card text-muted me-1"></i>
                                        {{ str_replace('_', ' ', ucfirst($expense->payment_method)) }}
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'paid' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                            $color = $statusColors[$expense->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($expense->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">{{ __('No expenses found') }}</p>
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                                            <i class="fas fa-plus me-2"></i>{{ __('Add First Expense') }}
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add New Expense') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Category') }} <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="">{{ __('Select Category') }}</option>
                                <option value="office">Office Supplies</option>
                                <option value="transport">Transportation</option>
                                <option value="utilities">Utilities</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="marketing">Marketing</option>
                                <option value="rent">Rent</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Expense Date') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Brief title of the expense" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control" rows="3" placeholder="Detailed description of the expense"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Amount') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="">{{ __('Select Method') }}</option>
                                <option value="cash">Cash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Vendor Name') }}</label>
                            <input type="text" class="form-control" placeholder="Name of the vendor/supplier">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Invoice Number') }}</label>
                            <input type="text" class="form-control" placeholder="Invoice/bill number">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Receipt/Document') }}</label>
                            <input type="file" class="form-control" accept="image/*,.pdf">
                            <small class="text-muted">{{ __('Upload receipt or supporting document') }}</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>{{ __('Add Expense') }}
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
    // Filter functionality
    const filters = document.querySelectorAll('select.form-select-sm, input.form-control-sm');
    filters.forEach(filter => {
        filter.addEventListener('change', function() {
            // Add filter logic here
            console.log('Filter changed');
        });
    });
});
</script>
@endpush