@extends('layouts.admin')

@section('title', 'Detalle de Registro de Actividad')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Detalle de Registro de Actividad #{{ $log->id }}</h2>
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Volver a la Lista
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Fecha y Hora:</th>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 150px;">Modelo:</th>
                                    <td>
                                        @if($log->model_type)
                                            {{ class_basename($log->model_type) }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>ID del Modelo:</th>
                                    <td>{{ $log->model_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Dirección IP:</th>
                                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Agente Usuario:</th>
                                    <td>
                                        <small class="text-muted">{{ $log->user_agent ?? 'N/A' }}</small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($log->properties)
                        <div class="mt-4">
                            <h4>Propiedades</h4>
                            <div class="card">
                                <div class="card-body bg-light">
                                    @if(isset($log->properties['old']) && isset($log->properties['new']))
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>Valores Anteriores</h5>
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Campo</th>
                                                            <th>Valor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($log->properties['old'] as $key => $value)
                                                            <tr>
                                                                <td>{{ $key }}</td>
                                                                <td>
                                                                    @if(is_array($value))
                                                                        <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                                    @else
                                                                        {{ $value }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>Nuevos Valores</h5>
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Campo</th>
                                                            <th>Valor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($log->properties['new'] as $key => $value)
                                                            <tr>
                                                                <td>{{ $key }}</td>
                                                                <td>
                                                                    @if(is_array($value))
                                                                        <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                                    @else
                                                                        {{ $value }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @elseif(isset($log->properties['attributes']) && isset($log->properties['old']))
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Atributo</th>
                                                        <th>Valor Anterior</th>
                                                        <th>Nuevo Valor</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($log->properties['attributes'] as $key => $newValue)
                                                        @if(array_key_exists($key, $log->properties['old']))
                                                            @php
                                                                $oldValue = $log->properties['old'][$key];
                                                                $changed = $oldValue != $newValue;
                                                            @endphp
                                                            <tr class="{{ $changed ? 'table-warning' : '' }}">
                                                                <td><strong>{{ $key }}</strong></td>
                                                                <td>{!! is_array($oldValue) ? '<pre>'.json_encode($oldValue, JSON_PRETTY_PRINT).'</pre>' : $oldValue !!}</td>
                                                                <td>{!! is_array($newValue) ? '<pre>'.json_encode($newValue, JSON_PRETTY_PRINT).'</pre>' : $newValue !!}</td>
                                                            </tr>
                                                        @else
                                                            <tr class="table-success">
                                                                <td><strong>{{ $key }}</strong></td>
                                                                <td><em class="text-muted">No existía</em></td>
                                                                <td>{!! is_array($newValue) ? '<pre>'.json_encode($newValue, JSON_PRETTY_PRINT).'</pre>' : $newValue !!}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <pre>{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
