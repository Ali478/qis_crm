@extends('layouts.app')

@section('title', __('Edit Shipment'))
@section('page-title', __('Edit Shipment'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">{{ __('Edit Shipment') }}: {{ $shipment->shipment_number }}</h2>
                <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back') }}
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('shipments.update', $shipment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>{{ __('Shipment Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Customer') }} <span class="text-danger">*</span></label>
                                <select name="customer_id" class="form-select" required>
                                    <option value="">{{ __('Select Customer') }}</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $shipment->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->company_name }} ({{ $customer->customer_code }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="draft" {{ $shipment->status == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                                    <option value="booked" {{ $shipment->status == 'booked' ? 'selected' : '' }}>{{ __('Booked') }}</option>
                                    <option value="in_transit" {{ $shipment->status == 'in_transit' ? 'selected' : '' }}>{{ __('In Transit') }}</option>
                                    <option value="customs" {{ $shipment->status == 'customs' ? 'selected' : '' }}>{{ __('At Customs') }}</option>
                                    <option value="out_for_delivery" {{ $shipment->status == 'out_for_delivery' ? 'selected' : '' }}>{{ __('Out for Delivery') }}</option>
                                    <option value="delivered" {{ $shipment->status == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Service Type') }} <span class="text-danger">*</span></label>
                                <select name="service_type" class="form-select" required>
                                    <option value="">{{ __('Select Service') }}</option>
                                    @foreach($dynamicOptions['service_type'] ?? [] as $option)
                                        <option value="{{ $option->value }}" {{ $shipment->service_type == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Transport Mode') }} <span class="text-danger">*</span></label>
                                <select name="transport_mode" class="form-select" required>
                                    <option value="">{{ __('Select Mode') }}</option>
                                    @foreach($dynamicOptions['transport_mode'] ?? [] as $option)
                                        <option value="{{ $option->value }}" {{ $shipment->transport_mode == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Shipment Type') }} <span class="text-danger">*</span></label>
                                <select name="shipment_type" class="form-select" required>
                                    <option value="">{{ __('Select Type') }}</option>
                                    @foreach($dynamicOptions['shipment_type'] ?? [] as $option)
                                        <option value="{{ $option->value }}" {{ $shipment->shipment_type == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Origin & Destination -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ __('Origin & Destination') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">{{ __('Origin') }}</h6>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="origin_country" class="form-control" value="{{ $shipment->origin_country }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="origin_city" class="form-control" value="{{ $shipment->origin_city }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success mb-3">{{ __('Destination') }}</h6>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="destination_country" class="form-control" value="{{ $shipment->destination_country }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="destination_city" class="form-control" value="{{ $shipment->destination_city }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <!-- Shipment Details -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-box me-2"></i>{{ __('Shipment Details') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Total Weight') }} <span class="text-danger">*</span></label>
                                <input type="number" name="total_weight" class="form-control" step="0.01" value="{{ $shipment->total_weight }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Weight Unit') }}</label>
                                <select name="weight_unit" class="form-select">
                                    @foreach($dynamicOptions['weight_unit'] ?? [] as $option)
                                        <option value="{{ $option->value }}" {{ ($shipment->weight_unit ?? 'kg') == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Total Volume') }}</label>
                                <input type="number" name="total_volume" class="form-control" step="0.01" value="{{ $shipment->total_volume ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Volume Unit') }}</label>
                                <select name="volume_unit" class="form-select">
                                    @foreach($dynamicOptions['volume_unit'] ?? [] as $option)
                                        <option value="{{ $option->value }}" {{ ($shipment->volume_unit ?? 'm3') == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Freight Charge ($)') }} <span class="text-danger">*</span></label>
                            <input type="number" name="freight_charge" class="form-control" step="0.01" value="{{ $shipment->freight_charge }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Total Cost ($)') }} <span class="text-danger">*</span></label>
                            <input type="number" name="total_cost" class="form-control" step="0.01" value="{{ $shipment->total_cost }}" required>
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>{{ __('Important Dates') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Estimated Departure') }}</label>
                            <input type="date" name="estimated_departure" class="form-control" value="{{ $shipment->estimated_departure ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Estimated Arrival') }}</label>
                            <input type="date" name="estimated_arrival" class="form-control" value="{{ $shipment->estimated_arrival ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Actual Delivery Date') }}</label>
                            <input type="date" name="actual_delivery_date" class="form-control" value="{{ $shipment->actual_delivery_date ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save me-2"></i>{{ __('Update Shipment') }}
                        </button>
                        <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
