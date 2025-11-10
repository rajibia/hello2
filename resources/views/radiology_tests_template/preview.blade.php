@extends('layouts.app')

@section('title', 'Radiology Template Preview')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-eye me-2"></i>{{ __('Radiology Template Preview') }}
        </h4>
        <div>
            <a href="{{ route('radiology.test.templates.create') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>{{ __('Back to Create') }}
            </a>
            <a href="{{ route('radiology.test.template.index') }}" class="btn btn-primary">
                <i class="fas fa-list me-1"></i>{{ __('Back to Templates') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>{{ __('Template Information') }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>{{ __('Template Name:') }}</strong></td>
                            <td>{{ $previewData['test_name'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Short Name:') }}</strong></td>
                            <td>{{ $previewData['short_name'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Test Type:') }}</strong></td>
                            <td>{{ $previewData['test_type'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Report Days:') }}</strong></td>
                            <td>{{ $previewData['report_days'] ?? 'Not specified' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>{{ __('Standard Charge:') }}</strong></td>
                            <td>{{ $previewData['standard_charge'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Icon Class:') }}</strong></td>
                            <td>{{ $previewData['icon_class'] ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Icon Color:') }}</strong></td>
                            <td>
                                @if($previewData['icon_color'])
                                    <span class="badge" style="background-color: {{ $previewData['icon_color'] }}; color: white;">
                                        {{ $previewData['icon_color'] }}
                                    </span>
                                @else
                                    Not specified
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(isset($previewData['form_configuration']))
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>{{ __('Form Configuration') }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>{{ __('Table Type:') }}</strong></td>
                            <td>{{ ucfirst($previewData['form_configuration']['table_type']) }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Layout Type:') }}</strong></td>
                            <td>{{ ucfirst(str_replace('_', ' ', $previewData['form_configuration']['layout_type'])) }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Columns per Row:') }}</strong></td>
                            <td>{{ $previewData['form_configuration']['columns_per_row'] }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>{{ __('Specimen Name:') }}</strong></td>
                            <td>{{ $previewData['form_configuration']['specimen_name'] ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Field Groups:') }}</strong></td>
                            <td>
                                @if(isset($previewData['form_configuration']['field_groups']) && count($previewData['form_configuration']['field_groups']) > 0)
                                    @foreach($previewData['form_configuration']['field_groups'] as $group)
                                        <span class="badge bg-primary me-1">{{ $group }}</span>
                                    @endforeach
                                @else
                                    No groups defined
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if(isset($previewData['form_configuration']['fields']) && count($previewData['form_configuration']['fields']) > 0)
            <div class="mt-4">
                <h6 class="text-primary"><i class="fas fa-list me-2"></i>{{ __('Form Fields') }}</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('Field Name') }}</th>
                                <th>{{ __('Label') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Group') }}</th>
                                <th>{{ __('Required') }}</th>
                                <th>{{ __('Unit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($previewData['form_configuration']['fields'] as $field)
                            <tr>
                                <td>{{ $field['name'] }}</td>
                                <td>{{ $field['label'] }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($field['type']) }}</span></td>
                                <td>{{ $field['group'] ?? 'General' }}</td>
                                <td>
                                    @if($field['required'] == '1')
                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td>{{ $field['unit'] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($previewData['form_configuration']['table_type'] === 'species_dependent' && isset($previewData['form_configuration']['species_config']))
            <div class="mt-4">
                <h6 class="text-success"><i class="fas fa-project-diagram me-2"></i>{{ __('Species Configuration') }}</h6>
                <div class="alert alert-info">
                    <strong>{{ __('Results Options:') }}</strong> {{ $previewData['form_configuration']['species_config']['results'] ?? 'Not configured' }}<br>
                    <strong>{{ __('Unit Options:') }}</strong> {{ $previewData['form_configuration']['species_config']['units'] ?? 'Not configured' }}
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
