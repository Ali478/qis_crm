@extends('layouts.app')

@section('title', 'Shipment Details - Quick International Shipping Company')

@section('page-title', __('Shipment') . ' #' . ($shipment->shipment_number ?? ''))

@section('content')
<div class="container-fluid">
    <!-- Shipment Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-2">{{ $shipment->shipment_number }}</h3>
                            <p class="mb-1">
                                <i class="fas fa-user me-2"></i>{{ $shipment->company_name }}
                                <span class="ms-3">
                                    <i class="fas fa-calendar me-2"></i>{{ date('M d, Y', strtotime($shipment->created_at)) }}
                                </span>
                            </p>
                            <div>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'confirmed' => 'info',
                                        'in_transit' => 'primary',
                                        'out_for_delivery' => 'info',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                        'on_hold' => 'secondary'
                                    ];
                                    $color = $statusColors[$shipment->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} fs-6">
                                    {{ str_replace('_', ' ', ucfirst($shipment->status)) }}
                                </span>
                                <span class="badge bg-light text-dark ms-2 fs-6">{{ ucfirst($shipment->service_type) }}</span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('shipments.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                            <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-primary ms-2">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                            <button class="btn btn-warning ms-2">
                                <i class="fas fa-print me-2"></i>Print Label
                            </button>
                            <button class="btn btn-danger ms-2" onclick="confirmDelete({{ $shipment->id }})">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                            <form id="delete-form-{{ $shipment->id }}" action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Timeline -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-route text-primary me-2"></i>{{ __('Tracking Timeline') }}</h6>
                </div>
                <div class="card-body">
                    <div class="tracking-timeline">
                        @php
                            // Status to icon and color mapping
                            $statusConfig = [
                                'booked' => ['icon' => 'fa-calendar-check', 'color' => 'info', 'label' => 'Booked'],
                                'draft' => ['icon' => 'fa-file-alt', 'color' => 'secondary', 'label' => 'Draft'],
                                'pending' => ['icon' => 'fa-clock', 'color' => 'warning', 'label' => 'Pending'],
                                'picked_up' => ['icon' => 'fa-truck-loading', 'color' => 'primary', 'label' => 'Picked Up'],
                                'in_transit' => ['icon' => 'fa-plane', 'color' => 'primary', 'label' => 'In Transit'],
                                'at_port' => ['icon' => 'fa-anchor', 'color' => 'info', 'label' => 'At Port'],
                                'customs' => ['icon' => 'fa-passport', 'color' => 'warning', 'label' => 'At Customs'],
                                'customs_clearance' => ['icon' => 'fa-file-signature', 'color' => 'warning', 'label' => 'Customs Clearance'],
                                'out_for_delivery' => ['icon' => 'fa-truck', 'color' => 'info', 'label' => 'Out for Delivery'],
                                'delivered' => ['icon' => 'fa-check-circle', 'color' => 'success', 'label' => 'Delivered'],
                                'cancelled' => ['icon' => 'fa-times-circle', 'color' => 'danger', 'label' => 'Cancelled'],
                            ];

                            // Build tracking events from database records
                            $trackingEvents = [];
                            
                            // Convert tracking records to events array
                            foreach ($tracking as $track) {
                                $config = $statusConfig[$track->status] ?? ['icon' => 'fa-info-circle', 'color' => 'secondary', 'label' => ucfirst($track->status)];
                                $trackingEvents[] = [
                                    'date' => $track->timestamp,
                                    'status' => $config['label'],
                                    'location' => $track->location ?? 'Unknown',
                                    'description' => $track->description ?? '',
                                    'icon' => $config['icon'],
                                    'color' => $config['color'],
                                    'is_completed' => true, // All database records are completed
                                    'status_key' => $track->status
                                ];
                            }

                            // Sort by timestamp (oldest first)
                            usort($trackingEvents, function($a, $b) {
                                return strtotime($a['date']) - strtotime($b['date']);
                            });

                            // Determine which events are completed based on current shipment status
                            $currentStatus = $shipment->status;
                            $statusOrder = ['draft', 'booked', 'pending', 'picked_up', 'in_transit', 'at_port', 'customs', 'customs_clearance', 'out_for_delivery', 'delivered', 'cancelled'];
                            $currentStatusIndex = array_search($currentStatus, $statusOrder);

                            // Mark events as completed/incomplete
                            foreach ($trackingEvents as &$event) {
                                $eventStatusIndex = array_search($event['status_key'], $statusOrder);
                                if ($eventStatusIndex !== false && $eventStatusIndex <= $currentStatusIndex) {
                                    $event['is_completed'] = true;
                                } else {
                                    $event['is_completed'] = false;
                                }
                            }
                            unset($event);

                            // Add expected delivery if not delivered and ETA exists
                            if ($shipment->status !== 'delivered' && $shipment->estimated_arrival) {
                                $trackingEvents[] = [
                                    'date' => $shipment->estimated_arrival,
                                    'status' => 'Expected Delivery',
                                    'location' => $shipment->destination_city ?? 'Destination',
                                    'description' => 'Estimated delivery date',
                                    'icon' => 'fa-home',
                                    'color' => 'secondary',
                                    'is_completed' => false,
                                    'status_key' => 'expected_delivery'
                                ];
                            }

                            // If no tracking records, add initial event from shipment creation
                            if (empty($trackingEvents)) {
                                $config = $statusConfig[$shipment->status] ?? ['icon' => 'fa-box', 'color' => 'info', 'label' => 'Shipment Created'];
                                $trackingEvents[] = [
                                    'date' => $shipment->created_at,
                                    'status' => 'Shipment Created',
                                    'location' => $shipment->origin_city ?? 'Origin',
                                    'description' => 'Shipment was created',
                                    'icon' => 'fa-box',
                                    'color' => 'success',
                                    'is_completed' => true,
                                    'status_key' => 'created'
                                ];
                            }
                        @endphp

                        @forelse($trackingEvents as $index => $event)
                        <div class="timeline-item {{ $event['is_completed'] ? 'completed' : '' }}">
                            <div class="timeline-marker bg-{{ $event['color'] }}">
                                <i class="fas {{ $event['icon'] }} text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $event['status'] }}</h6>
                                @if(!empty($event['description']))
                                <p class="mb-1 small text-muted">{{ $event['description'] }}</p>
                                @endif
                                <p class="text-muted mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $event['location'] }}
                                </p>
                                <small class="text-muted">{{ date('M d, Y h:i A', strtotime($event['date'])) }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-route fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('No tracking information available') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Shipment Information -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle text-primary me-2"></i>{{ __('Shipment Information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Service Type') }}</label>
                            <p class="mb-0 fw-bold">{{ ucfirst($shipment->service_type) }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Transport Mode') }}</label>
                            <p class="mb-0 fw-bold">{{ ucfirst($shipment->transport_mode) }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Shipment Type') }}</label>
                            <p class="mb-0 fw-bold">{{ $shipment->shipment_type }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Total Pieces') }}</label>
                            <p class="mb-0 fw-bold">{{ $shipment->total_pieces ?? '-' }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Total Weight') }}</label>
                            <p class="mb-0 fw-bold">{{ $shipment->total_weight }} kg</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">{{ __('Total Volume') }}</label>
                            <p class="mb-0 fw-bold">{{ $shipment->total_volume ?? '-' }} m³</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">{{ __('Estimated Arrival') }}</label>
                            <p class="mb-0 fw-bold">
                                @if($shipment->estimated_arrival)
                                    {{ date('M d, Y h:i A', strtotime($shipment->estimated_arrival)) }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-dollar-sign text-primary me-2"></i>{{ __('Pricing Details') }}</h6>
                </div>
                <div class="card-body">
                    <div class="pricing-breakdown">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('Freight Charge') }}:</span>
                            <strong>${{ number_format($shipment->freight_charge, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('Handling Charge') }}:</span>
                            <strong>${{ number_format($shipment->handling_charge ?? 0, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('Other Charges') }}:</span>
                            <strong>${{ number_format($shipment->other_charges ?? 0, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h6>{{ __('Total Cost') }}:</h6>
                            <h5 class="text-primary">${{ number_format($shipment->total_cost, 2) }}</h5>
                        </div>
                        <div class="mt-3">
                            <label class="text-muted small">{{ __('Payment Status') }}</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ ($shipment->payment_status ?? 'pending') == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($shipment->payment_status ?? 'pending') }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Origin & Destination -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ __('Origin') }}</h6>
                </div>
                <div class="card-body">
                    <h6>{{ __('From') }}:</h6>
                    <p class="mb-1">
                        <strong>{{ $shipment->origin_city }}, {{ $shipment->origin_country }}</strong>
                    </p>
                    @if($shipment->origin_address)
                    <p class="text-muted">{{ $shipment->origin_address }}</p>
                    @endif
                    <div class="mt-3">
                        <button class="btn btn-sm btn-outline-success">
                            <i class="fas fa-map me-1"></i>View on Map
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ __('Destination') }}</h6>
                </div>
                <div class="card-body">
                    <h6>{{ __('To') }}:</h6>
                    <p class="mb-1">
                        <strong>{{ $shipment->destination_city }}, {{ $shipment->destination_country }}</strong>
                    </p>
                    @if($shipment->destination_address)
                    <p class="text-muted">{{ $shipment->destination_address }}</p>
                    @endif
                    <div class="mt-3">
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-map me-1"></i>View on Map
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents & Actions -->
    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-file-alt text-primary me-2"></i>{{ __('Documents') }}</h6>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="fas fa-upload me-2"></i>{{ __('Upload Document') }}
                    </button>
                </div>
                <div class="card-body">
                    @if(count($documents) > 0)
                        <div class="list-group">
                            @foreach($documents as $document)
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    @php
                                        $iconColors = [
                                            'bill_of_lading' => 'danger',
                                            'commercial_invoice' => 'primary',
                                            'packing_list' => 'info',
                                            'certificate_of_origin' => 'success',
                                            'customs_declaration' => 'warning',
                                            'other' => 'secondary'
                                        ];
                                        $color = $iconColors[$document->document_type] ?? 'secondary';
                                    @endphp
                                    <i class="fas fa-file-{{ $document->file_type == 'pdf' ? 'pdf' : 'alt' }} text-{{ $color }} me-2"></i>
                                    <strong>{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</strong>
                                    <br>
                                    <small class="text-muted ms-4">{{ $document->document_name }} ({{ number_format($document->file_size / 1024, 2) }} KB)</small>
                                    @if($document->description)
                                    <br>
                                    <small class="text-muted ms-4">{{ $document->description }}</small>
                                    @endif
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('documents.view', $document->id) }}" class="btn btn-sm btn-outline-info" target="_blank" title="{{ __('View') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('documents.download', $document->id) }}" class="btn btn-sm btn-outline-success" title="{{ __('Download') }}">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteDoc({{ $document->id }})" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-doc-form-{{ $document->id }}" action="{{ route('documents.delete', $document->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('No documents uploaded yet') }}</p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                <i class="fas fa-upload me-2"></i>{{ __('Upload First Document') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-cog text-primary me-2"></i>{{ __('Actions') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit Shipment
                        </a>
                        <button class="btn btn-outline-info">
                            <i class="fas fa-sync me-2"></i>Update Status
                        </button>
                        <a href="{{ route('invoices.create') }}?shipment_id={{ $shipment->id }}" class="btn btn-outline-success">
                            <i class="fas fa-file-invoice me-2"></i>Create Invoice
                        </a>
                        <button class="btn btn-outline-warning">
                            <i class="fas fa-envelope me-2"></i>Send Notification
                        </button>
                        <button class="btn btn-outline-danger" onclick="confirmDelete({{ $shipment->id }})">
                            <i class="fas fa-times-circle me-2"></i>Cancel Shipment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Special Instructions -->
    @if($shipment->special_instructions)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('Special Instructions') }}</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $shipment->special_instructions }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #667eea 0%, #4ca2cd 100%);
}

.tracking-timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 60px;
    padding-bottom: 30px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 40px;
    bottom: -10px;
    width: 2px;
    background: #dee2e6;
}

.timeline-item.completed:not(:last-child)::before {
    background: #28a745;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}
</style>
@endpush

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentModalLabel">
                    <i class="fas fa-upload me-2"></i>{{ __('Upload Document') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('documents.upload', $shipment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Document Type') }} <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select" required>
                            <option value="">{{ __('Select Document Type') }}</option>
                            <option value="bill_of_lading">{{ __('Bill of Lading') }}</option>
                            <option value="commercial_invoice">{{ __('Commercial Invoice') }}</option>
                            <option value="packing_list">{{ __('Packing List') }}</option>
                            <option value="certificate_of_origin">{{ __('Certificate of Origin') }}</option>
                            <option value="customs_declaration">{{ __('Customs Declaration') }}</option>
                            <option value="insurance_certificate">{{ __('Insurance Certificate') }}</option>
                            <option value="delivery_receipt">{{ __('Delivery Receipt') }}</option>
                            <option value="other">{{ __('Other') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Select File') }} <span class="text-danger">*</span></label>
                        <input type="file" name="document_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                        <small class="text-muted">{{ __('Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max 10MB)') }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Description') }}</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="{{ __('Optional notes about this document') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>{{ __('Upload') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this shipment? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

function confirmDeleteDoc(id) {
    if (confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
        document.getElementById('delete-doc-form-' + id).submit();
    }
}
</script>
@endpush