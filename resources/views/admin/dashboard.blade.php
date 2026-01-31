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
        <!-- Gráfico de participación por colonia -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Participación por Colonia</h3>
                </div>
                <div class="card-body">
                    <canvas id="coloniaChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.encuestas.index') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-list"></i> Ver Todas las Encuestas
                        </a>
                        <a href="{{ route('admin.export.encuestas') }}" class="btn btn-success btn-block">
                            <i class="fas fa-download"></i> Exportar a Excel
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-info btn-block" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Ver Sitio Público
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Encuestas recientes -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Encuestas Recientes</h3>
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

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de participación por colonia
    var ctx = document.getElementById('coloniaChart').getContext('2d');
    var coloniaChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($encuestasPorColonia->pluck('colonia.nombre')) !!},
            datasets: [{
                label: 'Número de Encuestas',
                data: {!! json_encode($encuestasPorColonia->pluck('total')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@stop
