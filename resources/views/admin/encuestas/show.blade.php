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

    <!-- Seguridad Pública -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-shield-alt mr-2"></i>Seguridad Pública
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Columna 1: Preguntas básicas -->
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-6">Servicio de Seguridad:</dt>
                                <dd class="col-sm-6">
                                    <span class="badge badge-info">{{ $encuesta->servicio_seguridad ?? 'N/A' }}</span>
                                </dd>

                                <dt class="col-sm-6">Confía en Policía:</dt>
                                <dd class="col-sm-6">
                                    <span class="badge badge-{{ $encuesta->confia_policia == 'Sí' ? 'success' : ($encuesta->confia_policia == 'No' ? 'danger' : 'secondary') }}">
                                        {{ $encuesta->confia_policia ?? 'N/A' }}
                                    </span>
                                </dd>

                                <dt class="col-sm-6">Horario Inseguro:</dt>
                                <dd class="col-sm-6">{{ $encuesta->horario_inseguro ?? 'N/A' }}</dd>
                            </dl>
                        </div>

                        <!-- Columna 2: Escalas de seguridad -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Escalas de Seguridad (1-10)</h5>
                            <div class="row">
                                @if($encuesta->emergencia_transporte)
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted">Emergencia y transporte:</small>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-danger" style="width: {{ ($encuesta->emergencia_transporte/10)*100 }}%">
                                            {{ $encuesta->emergencia_transporte }}/10
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($encuesta->caminar_noche)
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted">Caminar de noche:</small>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-warning" style="width: {{ ($encuesta->caminar_noche/10)*100 }}%">
                                            {{ $encuesta->caminar_noche }}/10
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($encuesta->hijos_solos)
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted">Hijos caminando solos:</small>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" style="width: {{ ($encuesta->hijos_solos/10)*100 }}%">
                                            {{ $encuesta->hijos_solos }}/10
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($encuesta->transporte_publico)
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted">Transporte público:</small>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: {{ ($encuesta->transporte_publico/10)*100 }}%">
                                            {{ $encuesta->transporte_publico }}/10
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Problemas de Seguridad -->
                    @if($encuesta->problemas_seguridad && is_array($encuesta->problemas_seguridad) && count($encuesta->problemas_seguridad) > 0)
                    <hr>
                    <h5 class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Problemas de Seguridad Calificados
                    </h5>
                    <div class="row">
                        @php
                            $problemasTexto = [
                                'Corrupción de los elementos de seguridad',
                                'Robo a casa habitación',
                                'Asaltos a transeúntes',
                                'Robo de vehículos, motos o autopartes',
                                'Extorsión por llamada telefónica',
                                'Venta de sustancias ilícitas (drogas)',
                                'Falta de vigilancia y presencia de policías',
                                'Venta y/o consumo de alcohol en la calle',
                                'Violencia familiar, o contra las mujeres, niñas o niños',
                                'Violencia en contra de los y las adultos mayores',
                                'Violencia en contra de los animales domésticos o mascotas',
                                'Violencia en contra de las personas discapacitadas',
                                'Bullying en las escuelas',
                                'Acoso o molestias en la calle a mujeres, señoritas, niñas',
                                'Actos de discriminación o molestia a personas LGTTBIQ+',
                                'Riñas, peleas o lesiones entre vecinos',
                                'Venta y/o consumo de sustancias ilícitas (drogas) en la calle'
                            ];
                            $nivelesTexto = [1 => 'No me preocupa', 2 => 'Me preocupa poco', 3 => 'Más o menos me preocupa', 4 => 'Me preocupa mucho'];
                        @endphp
                        @foreach($encuesta->problemas_seguridad as $index => $nivel)
                            @if(isset($problemasTexto[$index]))
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $problemasTexto[$index] }}</small>
                                    <span class="badge badge-{{ $nivel == 4 ? 'danger' : ($nivel == 3 ? 'warning' : ($nivel == 2 ? 'info' : 'secondary')) }}">
                                        {{ $nivelesTexto[$nivel] ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @endif

                    <!-- Lugares Seguros -->
                    @if($encuesta->lugares_seguros && is_array($encuesta->lugares_seguros) && count($encuesta->lugares_seguros) > 0)
                    <hr>
                    <h5 class="mb-3">
                        <i class="fas fa-map-marker-alt text-success mr-2"></i>Percepción de Seguridad en Lugares
                    </h5>
                    <div class="row">
                        @php
                            $lugaresTexto = [
                                'Un parque de su comunidad',
                                'En el mercado o tianguis',
                                'Al visitar una plaza comercial o un supermercado',
                                'En un cajero automático',
                                'A bordo de un camión, micro o vagoneta de transporte público de pasajeros',
                                'Al exterior de una escuela',
                                'Caminando en las calles cercanas a su domicilio',
                                'En el Municipio de Tecámac'
                            ];
                            $seguridadTexto = [1 => 'Totalmente inseguros', 2 => 'Poco seguros', 3 => 'Más o menos seguros', 4 => 'Seguros'];
                        @endphp
                        @foreach($encuesta->lugares_seguros as $index => $nivel)
                            @if(isset($lugaresTexto[$index]))
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $lugaresTexto[$index] }}</small>
                                    <span class="badge badge-{{ $nivel == 4 ? 'success' : ($nivel == 3 ? 'info' : ($nivel == 2 ? 'warning' : 'danger')) }}">
                                        {{ $seguridadTexto[$nivel] ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
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
