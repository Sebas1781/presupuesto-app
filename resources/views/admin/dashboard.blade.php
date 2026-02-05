@extends('adminlte::page')

@section('title', 'Dashboard - Presupuesto Participativo 2026')

@section('content_header')
    <h1>Dashboard - Presupuesto Participativo 2026</h1>
@stop

@section('content')
    <div class="row">
        <!-- Estadísticas generales -->
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalEncuestas }}</h3>
                    <p>Total Encuestas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-poll"></i>
                </div>
                <a href="{{ route('admin.encuestas.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalPropuestas }}</h3>
                    <p>Total Propuestas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalReportes }}</h3>
                    <p>Total Reportes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $encuestasPorColonia->count() }}</h3>
                    <p>Colonias Participantes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Acciones rápidas -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt mr-2"></i>Acciones Rápidas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.encuestas.index') }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-list"></i> Ver Todas las Encuestas
                        </a>
                        <a href="{{ route('admin.estadisticas') }}" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-chart-bar"></i> Ver Estadísticas Completas
                        </a>
                        <a href="{{ route('admin.export.encuestas') }}" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-download"></i> Exportar a Excel
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-secondary btn-block" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Ver Sitio Público
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen rápido -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>Resumen General
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <h5><i class="icon fas fa-check"></i> Sistema Operativo</h5>
                                El sistema de encuestas está funcionando correctamente.
                                <strong>{{ $totalEncuestas }}</strong> ciudadanos han participado hasta el momento.
                            </div>
                        </div>
                    </div>

                    @if($encuestasPorColonia->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <h6><i class="fas fa-map-marker-alt mr-2"></i>Colonias con Mayor Participación:</h6>
                            <ul class="list-unstyled">
                                @foreach($encuestasPorColonia->sortByDesc('total')->take(5) as $item)
                                <li>
                                    <span class="badge badge-primary">{{ $item->total }}</span>
                                    {{ $item->colonia->nombre ?? 'Colonia no encontrada' }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Encuestas recientes -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-2"></i>Encuestas Recientes
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Colonia</th>
                                <th>Género</th>
                                <th>Edad</th>
                                <th>Propuestas</th>
                                <th>Reportes</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($encuestasRecientes as $encuesta)
                            <tr>
                                <td>{{ $encuesta->id }}</td>
                                <td>{{ $encuesta->colonia->nombre ?? 'N/A' }}</td>
                                <td>{{ $encuesta->genero }}</td>
                                <td>{{ $encuesta->edad }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $encuesta->propuestas->count() }}</span>
                                </td>
                                <td>
                                    @if($encuesta->reportes->count() > 0)
                                        <span class="badge badge-warning">{{ $encuesta->reportes->count() }}</span>
                                    @else
                                        <span class="badge badge-secondary">0</span>
                                    @endif
                                </td>
                                <td>{{ $encuesta->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.encuestas.show', $encuesta->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
