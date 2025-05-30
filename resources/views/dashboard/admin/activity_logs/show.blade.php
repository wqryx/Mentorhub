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
                        <a href="{{ route('admin.activity_logs.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Volver a la Lista
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 150px;">ID:</th>
                                    <td>{{ $log->id }}</td>
                                </tr>
                                <tr>
                                    <th>Usuario:</th>
                                    <td>
                                        @if($log->user)
                                            <a href="{{ route('admin.users.show', $log->user_id) }}">
                                                {{ $log->user->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Sistema</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Acción:</th>
                                    <td>
                                        <span class="badge {{ getBadgeClass($log->action) }}">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Descripción:</th>
                                    <td>{{ $log->description }}</td>
                                </tr>
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

@php
function getBadgeClass($action) {
    switch ($action) {
        case 'created':
            return 'bg-success';
        case 'updated':
            return 'bg-info';
        case 'deleted':
            return 'bg-danger';
        case 'login':
            return 'bg-primary';
        case 'logout':
            return 'bg-secondary';
        default:
            return 'bg-dark';
    }
}
@endphp
