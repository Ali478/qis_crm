@extends('layouts.app')

@section('title', 'Shipments - Quick International Shipping Company')

@section('page-title', __('Shipments Management'))

@section('content')
<div class="container-fluid">
    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card glass-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ __('All Shipments') }}</h4>
                        <p class="text-muted mb-0">{{ __('Manage and track all shipments') }}</p>
                    </div>
                    <div>
                        @php
                            $hasActiveFilters = !empty($filters['status']) || !empty($filters['transport_mode']) || !empty($filters['date_from']) || !empty($filters['date_to']);
                        @endphp
                        <button class="btn btn-futuristic me-2 position-relative" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fas fa-filter me-2"></i>{{ __('Filter') }}
                            @if($hasActiveFilters)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </button>
                        @if($hasActiveFilters)
                            <a href="{{ route('shipments.index') }}" class="btn btn-outline-danger btn-sm me-2" title="{{ __('Clear All Filters') }}">
                                <i class="fas fa-times me-1"></i>{{ __('Clear') }}
                            </a>
                        @endif
                        <a href="{{ route('shipments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>{{ __('New Shipment') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Total Shipments') }}</div>
                <i class="fas fa-shipping-fast fa-2x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['in_transit'] ?? 0 }}</div>
                <div class="stat-label">{{ __('In Transit') }}</div>
                <i class="fas fa-truck-moving fa-2x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['delivered'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Delivered') }}</div>
                <i class="fas fa-check-circle fa-2x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
                <div class="stat-label">{{ __('Pending') }}</div>
                <i class="fas fa-clock fa-2x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
            </div>
        </div>
    </div>

    <!-- Shipments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header gradient-text">
                    <h5 class="mb-0">{{ __('Shipments List') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Shipment #') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Origin') }}</th>
                                    <th>{{ __('Destination') }}</th>
                                    <th>{{ __('Mode') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Booking Date') }}</th>
                                    <th>{{ __('ETA') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shipments as $shipment)
                                <tr class="table-row-hover">
                                    <td class="position-relative">
                                        <div>
                                            <strong>{{ $shipment->shipment_number }}</strong>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($shipment->service_type) }}</small>
                                        </div>
                                        <div class="row-actions">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('shipments.show', $shipment->id) }}"
                                                   class="btn btn-outline-primary" title="{{ __('View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('shipments.show', $shipment->id) }}?print=1"
                                                   target="_blank"
                                                   class="btn btn-outline-info" title="{{ __('Print') }}">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <a href="{{ route('shipments.edit', $shipment->id) }}"
                                                   class="btn btn-outline-secondary" title="{{ __('Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $shipment->company_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $shipment->customer_code }}</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-primary"></i> {{ $shipment->origin_city }}
                                        <br>
                                        <small class="text-muted">{{ $shipment->origin_country }}</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-success"></i> {{ $shipment->destination_city }}
                                        <br>
                                        <small class="text-muted">{{ $shipment->destination_country }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $modeIcons = [
                                                'sea' => 'fa-ship',
                                                'air' => 'fa-plane',
                                                'land' => 'fa-truck',
                                                'rail' => 'fa-train'
                                            ];
                                            $icon = $modeIcons[$shipment->transport_mode] ?? 'fa-box';
                                        @endphp
                                        <i class="fas {{ $icon }}"></i> {{ ucfirst($shipment->transport_mode) }}
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'draft' => 'secondary',
                                                'booked' => 'info',
                                                'picked_up' => 'primary',
                                                'in_transit' => 'warning',
                                                'at_port' => 'info',
                                                'customs_clearance' => 'warning',
                                                'out_for_delivery' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $color = $statusColors[$shipment->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ str_replace('_', ' ', ucfirst($shipment->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ date('M d, Y', strtotime($shipment->booking_date)) }}</td>
                                    <td>
                                        @if($shipment->estimated_arrival)
                                            {{ date('M d, Y', strtotime($shipment->estimated_arrival)) }}
                                        @else
                                            <span class="text-muted">TBD</span>
                                        @endif
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

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Filter Shipments') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('shipments.index') }}" method="GET" id="filterForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Status') }}</label>
                            <select name="status" class="form-select">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                                <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="booked" {{ ($filters['status'] ?? '') == 'booked' ? 'selected' : '' }}>{{ __('Booked') }}</option>
                                <option value="picked_up" {{ ($filters['status'] ?? '') == 'picked_up' ? 'selected' : '' }}>{{ __('Picked Up') }}</option>
                                <option value="in_transit" {{ ($filters['status'] ?? '') == 'in_transit' ? 'selected' : '' }}>{{ __('In Transit') }}</option>
                                <option value="at_port" {{ ($filters['status'] ?? '') == 'at_port' ? 'selected' : '' }}>{{ __('At Port') }}</option>
                                <option value="customs" {{ ($filters['status'] ?? '') == 'customs' ? 'selected' : '' }}>{{ __('At Customs') }}</option>
                                <option value="customs_clearance" {{ ($filters['status'] ?? '') == 'customs_clearance' ? 'selected' : '' }}>{{ __('Customs Clearance') }}</option>
                                <option value="out_for_delivery" {{ ($filters['status'] ?? '') == 'out_for_delivery' ? 'selected' : '' }}>{{ __('Out for Delivery') }}</option>
                                <option value="delivered" {{ ($filters['status'] ?? '') == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                                <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Transport Mode') }}</label>
                            <select name="transport_mode" class="form-select">
                                <option value="">{{ __('All Modes') }}</option>
                                @if(isset($dynamicOptions['transport_mode']) && count($dynamicOptions['transport_mode']) > 0)
                                    @foreach($dynamicOptions['transport_mode'] as $option)
                                        <option value="{{ $option->value }}" {{ ($filters['transport_mode'] ?? '') == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                @else
                                    <option value="sea" {{ ($filters['transport_mode'] ?? '') == 'sea' ? 'selected' : '' }}>Sea</option>
                                    <option value="air" {{ ($filters['transport_mode'] ?? '') == 'air' ? 'selected' : '' }}>Air</option>
                                    <option value="land" {{ ($filters['transport_mode'] ?? '') == 'land' ? 'selected' : '' }}>Land</option>
                                    <option value="rail" {{ ($filters['transport_mode'] ?? '') == 'rail' ? 'selected' : '' }}>Rail</option>
                                    <option value="multimodal" {{ ($filters['transport_mode'] ?? '') == 'multimodal' ? 'selected' : '' }}>Multimodal</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Date From') }}</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Date To') }}</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                    <a href="{{ route('shipments.index') }}" class="btn btn-outline-danger">{{ __('Clear Filters') }}</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Apply Filters') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Tracking Modal -->
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Shipment Tracking') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="trackingContent">
                    <!-- Tracking info will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function trackShipment(shipmentNumber) {
    $('#trackingModal').modal('show');
    $('#trackingContent').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading tracking information...</p>
        </div>
    `);

    // Simulate loading tracking info
    setTimeout(() => {
        $('#trackingContent').html(`
            <div class="tracking-timeline">
                <h6 class="mb-3">Shipment: ${shipmentNumber}</h6>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6>Delivered</h6>
                            <p class="text-muted">Package delivered successfully</p>
                            <small>Sep 28, 2024 - 10:00 AM</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>Out for Delivery</h6>
                            <p class="text-muted">Package is out for delivery</p>
                            <small>Sep 28, 2024 - 08:00 AM</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6>In Transit</h6>
                            <p class="text-muted">Package in transit to destination</p>
                            <small>Sep 27, 2024 - 02:00 PM</small>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }, 1000);
}

// No JavaScript needed - actions are positioned with CSS within the row scope
</script>

<style>
/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

/* Hover Actions - Overlay on Text */
.shipment-row {
    transition: background 0.2s ease;
}

.shipment-row td.position-relative > div:first-child {
    position: relative;
    z-index: 1;
}

.shipment-row .row-actions {
    position: absolute;
    left: 35%;
    top: 90%;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease, visibility 0.2s ease;
    z-index: 10;
}

.shipment-row:hover .row-actions {
    opacity: 1;
    visibility: visible;
}

.shipment-row:hover {
    background: rgba(99, 102, 241, 0.08);
}

.row-actions .btn-group {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    padding: 0.35rem;
    display: flex;
    gap: 0.25rem;
}

.row-actions .btn-group .btn {
    border: none;
    background: transparent;
    color: #6366f1;
    padding: 0.5rem 0.65rem;
    border-radius: 0.375rem;
}

.row-actions .btn-group .btn:hover {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    transform: scale(1.1);
}
</style>
@endpush