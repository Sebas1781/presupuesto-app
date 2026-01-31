@extends('adminlte::page')

@section('title', 'Ver Encuesta #' . $encuesta->id)

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Encuesta #{{ $encuesta->id }}</h1>
        <a href="{{ route('admin.encuestas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Datos Sociodemográficos -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Datos Sociodemográficos</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Colonia:</dt>
                        <dd class="col-sm-8">{{ $encuesta->colonia->nombre ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Género:</dt>
                        <dd class="col-sm-8">{{ $encuesta->genero }}</dd>

                        <dt class="col-sm-4">Edad:</dt>
                        <dd class="col-sm-8">{{ $encuesta->edad }} años</dd>

                        <dt class="col-sm-4">Nivel Educativo:</dt>
                        <dd class="col-sm-8">{{ $encuesta->nivel_educativo }}</dd>

                        <dt class="col-sm-4">Estado Civil:</dt>
                        <dd class="col-sm-8">{{ $encuesta->estado_civil }}</dd>

                        <dt class="col-sm-4">Fecha:</dt>
                        <dd class="col-sm-8">{{ $encuesta->created_at->format('d/m/Y H:i:s') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Prioridad de Obras -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Prioridad de Obras</h3>
                </div>
                <div class="card-body">
                    @if(isset($obrasCalificadasConNombres) && count($obrasCalificadasConNombres) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Obra</th>
                                        <th>Calificación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($obrasCalificadasConNombres as $obra)
                                        <tr>
                                            <td>{{ $obra['nombre'] }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar progress-bar-striped"
                                                         style="width: {{ ($obra['calificacion']/5)*100 }}%">
                                                        {{ $obra['calificacion'] }}/5
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No hay calificaciones registradas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Propuestas -->
    @if($encuesta->propuestas->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Propuestas ({{ $encuesta->propuestas->count() }})</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($encuesta->propuestas as $index => $propuesta)
                        <div class="col-md-6 mb-4">
                            <div class="card border-info">
                                <div class="card-header bg-info">
                                    <h5 class="card-title mb-0 text-white">Propuesta {{ $index + 1 }}</h5>
                                </div>
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-5">Tipo:</dt>
                                        <dd class="col-sm-7">{{ $propuesta->tipo_propuesta }}</dd>

                                        <dt class="col-sm-5">Prioridad:</dt>
                                        <dd class="col-sm-7">
                                            <span class="badge
                                                @switch($propuesta->nivel_prioridad)
                                                    @case('Urgente') badge-danger @break
                                                    @case('Alta') badge-warning @break
                                                    @case('Normal') badge-info @break
                                                    @case('Baja') badge-secondary @break
                                                    @default badge-light
                                                @endswitch
                                            ">{{ $propuesta->nivel_prioridad }}</span>
                                        </dd>

                                        <dt class="col-sm-5">Beneficiados:</dt>
                                        <dd class="col-sm-7">{{ $propuesta->personas_beneficiadas }}</dd>

                                        <dt class="col-sm-5">Ubicación:</dt>
                                        <dd class="col-sm-7">{{ $propuesta->ubicacion ?: 'No especificada' }}</dd>
                                    </dl>

                                    @if($propuesta->descripcion_breve)
                                    <div class="mt-3">
                                        <strong>Descripción:</strong>
                                        <p class="mt-1">{{ $propuesta->descripcion_breve }}</p>
                                    </div>
                                    @endif

                                    @if($propuesta->fotografia)
                                    <div class="mt-3">
                                        <strong>Fotografía:</strong><br>
                                        <img src="{{ asset('storage/' . $propuesta->fotografia) }}"
                                             alt="Fotografía de propuesta" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Reportes -->
    @if($encuesta->reportes->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reportes Anónimos ({{ $encuesta->reportes->count() }})</h3>
                </div>
                <div class="card-body">
                    @foreach($encuesta->reportes as $index => $reporte)
                    <div class="card border-warning mb-3">
                        <div class="card-header bg-warning">
                            <h5 class="card-title mb-0">Reporte {{ $index + 1 }}</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Tipo:</dt>
                                <dd class="col-sm-9">{{ $reporte->tipo_reporte }}</dd>

                                <dt class="col-sm-3">Descripción:</dt>
                                <dd class="col-sm-9">{{ $reporte->descripcion }}</dd>

                                @if($reporte->ubicacion)
                                <dt class="col-sm-3">Ubicación:</dt>
                                <dd class="col-sm-9">{{ $reporte->ubicacion }}</dd>
                                @endif
                            </dl>

                            @if($reporte->evidencia)
                            <div class="mt-3">
                                <strong>Evidencia:</strong><br>
                                @if(str_contains($reporte->evidencia, '.pdf'))
                                    <a href="{{ asset('storage/' . $reporte->evidencia) }}"
                                       target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-file-pdf"></i> Ver PDF
                                    </a>
                                @else
                                    <img src="{{ asset('storage/' . $reporte->evidencia) }}"
                                         alt="Evidencia" class="img-thumbnail" style="max-width: 200px;">
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($encuesta->desea_reporte && $encuesta->reportes->count() == 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                El participante indicó que deseaba hacer un reporte anónimo, pero no se registraron reportes específicos.
            </div>
        </div>
    </div>
    @endif
@stop
