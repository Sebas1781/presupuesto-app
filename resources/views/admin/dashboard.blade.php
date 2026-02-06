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
            <div class="small-box bg-secondary">
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

    <!-- Encuestas recientes -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-clock mr-2"></i>Encuestas Recientes
                    </h3>
                </div>
                <div class="card-body">
                    @if($encuestasRecientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Colonia</th>
                                        <th>Género</th>
                                        <th>Edad</th>
                                        <th>Propuestas</th>
                                        <th>Reportes</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($encuestasRecientes as $encuesta)
                                    <tr>
                                        <td>{{ $encuesta->id }}</td>
                                        <td>{{ $encuesta->colonia->nombre }}</td>
                                        <td>{{ $encuesta->genero }}</td>
                                        <td>{{ $encuesta->edad }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $encuesta->propuestas->count() }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning">{{ $encuesta->reportes->count() }}</span>
                                        </td>
                                        <td>{{ $encuesta->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No hay encuestas disponibles</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Participación por Colonia -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #9D2449; color: white;">
                    <h3 class="card-title text-white">
                        <i class="fas fa-chart-bar mr-2"></i>Participación por Colonia
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="coloniaChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para ir a Estadísticas Detalladas -->
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <a href="{{ route('admin.estadisticas') }}" class="btn btn-lg" style="background-color: #9D2449; color: white;">
                <i class="fas fa-chart-line mr-2"></i>Ver Análisis Detallado por Distrito
            </a>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Paleta de colores Pantone corporativa
    const paletaColores = {
        pantone420: '#9D2449',    // Rojo corporativo principal
        pantone504: '#4E232E',    // Rojo oscuro
        pantone490: '#56242A',    // Rojo medio
        pantone465: '#B3865D'     // Beige/dorado
    };

    // Gráfico de Encuestas por Colonia
    var ctxColonia = document.getElementById('coloniaChart').getContext('2d');
    new Chart(ctxColonia, {
        type: 'bar',
        data: {
            labels: {!! json_encode($encuestasPorColonia->pluck('colonia.nombre')) !!},
            datasets: [{
                label: 'Número de Encuestas',
                data: {!! json_encode($encuestasPorColonia->pluck('total')) !!},
                backgroundColor: paletaColores.pantone420,
                borderColor: paletaColores.pantone504,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@stop