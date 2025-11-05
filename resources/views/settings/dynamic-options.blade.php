@extends('layouts.app')

@section('title', 'Dynamic Options - Quick International Shipping Company')

@section('page-title', __('Dynamic Options Management'))

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Manage Dynamic Options') }}</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOptionModal">
                    <i class="fas fa-plus me-2"></i>{{ __('Add New Option') }}
                </button>
            </div>
        </div>
    </div>

    @foreach($optionsByType as $type => $data)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="fas fa-list me-2"></i>{{ $data['label'] }}
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">{{ __('Order') }}</th>
                            <th width="20%">{{ __('Value') }}</th>
                            <th width="30%">{{ __('Label') }}</th>
                            <th width="10%">{{ __('Status') }}</th>
                            <th width="35%">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['options'] as $option)
                        <tr>
                            <td>{{ $option->sort_order }}</td>
                            <td><code>{{ $option->value }}</code></td>
                            <td>{{ $option->label }}</td>
                            <td>
                                @if($option->is_active)
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editOptionModal{{ $option->id }}">
                                    <i class="fas fa-edit"></i> {{ __('Edit') }}
                                </button>
                                <form action="{{ route('settings.dynamic-options.delete', $option->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this option?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> {{ __('Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">{{ __('No options found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($data['options'] as $option)
    <!-- Edit Modal for {{ $option->id }} -->
    <div class="modal fade" id="editOptionModal{{ $option->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Option') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('settings.dynamic-options.update', $option->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Option Type') }}</label>
                            <input type="text" class="form-control" value="{{ $optionTypes[$type] }}" readonly>
                            <input type="hidden" name="option_type" value="{{ $type }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Value') }} <span class="text-danger">*</span></label>
                            <input type="text" name="value" class="form-control" value="{{ $option->value }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Label') }} <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control" value="{{ $option->label }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Sort Order') }}</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ $option->sort_order }}" min="0">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active{{ $option->id }}" {{ $option->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active{{ $option->id }}">
                                    {{ __('Active') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update Option') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    @endforeach

    <!-- Add New Option Modal -->
    <div class="modal fade" id="addOptionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add New Option') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('settings.dynamic-options.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Option Type') }} <span class="text-danger">*</span></label>
                            <select name="option_type" class="form-select" required>
                                <option value="">{{ __('Select Option Type') }}</option>
                                @foreach($optionTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Value') }} <span class="text-danger">*</span></label>
                            <input type="text" name="value" class="form-control" required placeholder="e.g., standard, sea, FCL">
                            <small class="form-text text-muted">{{ __('Internal value used in the system') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Label') }} <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control" required placeholder="e.g., Standard Service, Sea Freight">
                            <small class="form-text text-muted">{{ __('Display name shown to users') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Sort Order') }}</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0">
                            <small class="form-text text-muted">{{ __('Lower numbers appear first in dropdowns') }}</small>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active_new" checked>
                                <label class="form-check-label" for="is_active_new">
                                    {{ __('Active') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Add Option') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

