@extends('layouts.app')

@section('title', 'Finance Overview - Quick International Shipping Company')

@section('page-title', __('Finance Dashboard'))

@section('content')
<div class="container-fluid">
    <!-- Financial Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-success">${{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Total Revenue') }}</div>
                <i class="fas fa-arrow-up fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-danger">${{ number_format($stats['total_expenses'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Total Expenses') }}</div>
                <i class="fas fa-arrow-down fa-2x text-danger opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-primary">${{ number_format($stats['net_profit'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Net Profit') }}</div>
                <i class="fas fa-chart-line fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value text-warning">${{ number_format($stats['pending_invoices'] ?? 0, 0) }}</div>
                <div class="stat-label">{{ __('Pending Invoices') }}</div>
                <i class="fas fa-clock fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Monthly Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Monthly Overview') }} - {{ date('F Y') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <h6 class="text-success mb-1">{{ __('Monthly Revenue') }}</h6>
                                    <h4 class="mb-0">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</h4>
                                </div>
                                <i class="fas fa-arrow-trend-up fa-2x text-success opacity-50"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <h6 class="text-danger mb-1">{{ __('Monthly Expenses') }}</h6>
                                    <h4 class="mb-0">${{ number_format($stats['monthly_expenses'] ?? 0, 2) }}</h4>
                                </div>
                                <i class="fas fa-arrow-trend-down fa-2x text-danger opacity-50"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <h6 class="text-info mb-1">{{ __('Monthly Profit') }}</h6>
                                    <h4 class="mb-0">${{ number_format($stats['monthly_profit'] ?? 0, 2) }}</h4>
                                </div>
                                <i class="fas fa-chart-pie fa-2x text-info opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <!-- Recent Revenue -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ __('Recent Revenue') }}</h6>
                    <a href="{{ route('finance.revenue') }}" class="btn btn-sm btn-light">{{ __('View All') }}</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentRevenue as $revenue)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $revenue->description }}</h6>
                                    <small class="text-muted">
                                        @if($revenue->company_name)
                                            <i class="fas fa-user me-1"></i>{{ $revenue->company_name }}
                                        @endif
                                        <span class="ms-2">
                                            <i class="fas fa-calendar me-1"></i>{{ date('M d, Y', strtotime($revenue->transaction_date)) }}
                                        </span>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <h6 class="text-success mb-0">+${{ number_format($revenue->amount, 2) }}</h6>
                                    <small class="badge bg-{{ $revenue->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($revenue->status) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">{{ __('No recent revenue') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Expenses -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ __('Recent Expenses') }}</h6>
                    <a href="{{ route('finance.expenses') }}" class="btn btn-sm btn-light">{{ __('View All') }}</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentExpenses as $expense)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $expense->title }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-tag me-1"></i>{{ ucfirst($expense->category) }}
                                        <span class="ms-2">
                                            <i class="fas fa-calendar me-1"></i>{{ date('M d, Y', strtotime($expense->expense_date)) }}
                                        </span>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <h6 class="text-danger mb-0">-${{ number_format($expense->amount, 2) }}</h6>
                                    <small class="badge bg-{{ $expense->status == 'paid' ? 'success' : ($expense->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($expense->status) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">{{ __('No recent expenses') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">{{ __('Quick Actions') }}</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>{{ __('Add Expense') }}
                        </a>
                        <a href="{{ route('invoices.create') }}" class="btn btn-success">
                            <i class="fas fa-file-invoice me-2"></i>{{ __('Create Invoice') }}
                        </a>
                        <a href="{{ route('finance.reports') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>{{ __('View Reports') }}
                        </a>
                        <button class="btn btn-warning">
                            <i class="fas fa-download me-2"></i>{{ __('Export Data') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add chart initialization here if needed
</script>
@endpush