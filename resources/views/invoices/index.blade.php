@extends('layouts.app')

@section('title', 'Invoices - Quick International Shipping Company')

@section('page-title', __('Invoice Management'))

@section('content')
<div class="container-fluid">
    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">${{ number_format($stats['total_amount'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Total Invoiced') }}</div>
                <i class="fas fa-file-invoice-dollar fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">${{ number_format($stats['paid_amount'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Paid Amount') }}</div>
                <i class="fas fa-check-circle fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">${{ number_format($stats['pending_amount'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Pending Amount') }}</div>
                <i class="fas fa-clock fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_invoices'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Total Invoices') }}</div>
                <i class="fas fa-file-alt fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ __('Invoices') }}</h4>
                        <p class="text-muted mb-0">{{ __('Manage customer invoices and payments') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>{{ __('Create Invoice') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Invoice #') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Shipment') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Paid') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                <tr class="table-row-hover">
                                    <td class="position-relative">
                                        <div>
                                            <strong>{{ $invoice->invoice_number }}</strong>
                                        </div>
                                        <div class="row-actions">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="{{ __('View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-info" title="{{ __('Download PDF') }}">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                @if($invoice->balance_amount > 0)
                                                <button class="btn btn-outline-success"
                                                        onclick="recordPayment({{ $invoice->id }})"
                                                        title="{{ __('Record Payment') }}">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </button>
                                                @endif
                                                <button class="btn btn-outline-secondary" title="{{ __('Send') }}">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $invoice->company_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $invoice->customer_code }}</small>
                                    </td>
                                    <td>
                                        @if($invoice->shipment_number)
                                            <span class="badge bg-secondary">{{ $invoice->shipment_number }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ date('M d, Y', strtotime($invoice->invoice_date)) }}</td>
                                    <td>
                                        @php
                                            $dueDate = strtotime($invoice->due_date);
                                            $today = strtotime(date('Y-m-d'));
                                            $isOverdue = $dueDate < $today && $invoice->status != 'paid';
                                        @endphp
                                        <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                            {{ date('M d, Y', $dueDate) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($invoice->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-success">${{ number_format($invoice->paid_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($invoice->balance_amount > 0)
                                            <span class="text-warning">${{ number_format($invoice->balance_amount, 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'draft' => 'secondary',
                                                'sent' => 'info',
                                                'viewed' => 'primary',
                                                'overdue' => 'danger',
                                                'paid' => 'success',
                                                'partially_paid' => 'warning',
                                                'cancelled' => 'dark'
                                            ];
                                            $color = $statusColors[$invoice->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ str_replace('_', ' ', ucfirst($invoice->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Record Payment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Payment Amount') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Payment Method') }}</label>
                        <select class="form-select" required>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="online">Online Payment</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Payment Date') }}</label>
                        <input type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Reference Number') }}</label>
                        <input type="text" class="form-control" placeholder="Transaction/Cheque number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('Record Payment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function recordPayment(invoiceId) {
    $('#paymentModal').modal('show');
    // You can load invoice details here
}
</script>
@endpush