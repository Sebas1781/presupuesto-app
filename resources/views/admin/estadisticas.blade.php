@extends('adminlte::page')

@section('title', 'Estadísticas - Presupuesto Participativo 2026')

@section('content_header')
    <h1>
        <i class="fas fa-chart-bar mr-2"></i>Estadísticas - Presupuesto Participativo 2026
    </h1>
@stop

@section('content')
    <!-- Estadísticas generales -->
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalEncuestas }}</h3>
                    <p>Total Encuestas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-poll"></i>
                </div>
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

    <!-- Gráfico de participación por colonia -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt mr-2"></i>Participación por Colonia
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="coloniaChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Seguridad Pública -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-shield-alt mr-2"></i>Estadísticas de Seguridad Pública
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Gráfica: Desconfianza en policía por edad -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Rangos de Edad que NO Confían en la Policía</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="desconfianzaEdadChart" width="400" height="300"></canvas>
                                    @if($desconfianzaPoliciaPorEdad->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Gráfica: Calificación del servicio de seguridad -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Calificación del Servicio de Seguridad</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="calificacionSeguridadChart" width="400" height="300"></canvas>
                                    @if($calificacionSeguridad->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Gráfica: Horarios de mayor inseguridad -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Horarios de Mayor Inseguridad Percibida</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="horariosInsegurosChart" width="400" height="200"></canvas>
                                    @if($horariosInseguros->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tarjetas de promedios -->
                        <div class="col-md-4">
                            <div class="row">
                                @if($promediosSeguridad && $promediosSeguridad->avg_emergencia)
                                <div class="col-12 mb-2">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h4>{{ number_format($promediosSeguridad->avg_emergencia, 1) }}/10</h4>
                                            <p>Emergencia y Transporte</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-ambulance"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h4>{{ number_format($promediosSeguridad->avg_caminar, 1) }}/10</h4>
                                            <p>Caminar de Noche</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-moon"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h4>{{ number_format($promediosSeguridad->avg_hijos, 1) }}/10</h4>
                                            <p>Hijos Caminando Solos</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-child"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h4>{{ number_format($promediosSeguridad->avg_transporte, 1) }}/10</h4>
                                            <p>Transporte Público</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-bus"></i>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        No hay suficientes datos de escalas de seguridad para mostrar promedios.
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
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

    // Gráfico de desconfianza en policía por edad
    @if(!$desconfianzaPoliciaPorEdad->isEmpty())
    var ctxDesconfianza = document.getElementById('desconfianzaEdadChart').getContext('2d');
    var desconfianzaChart = new Chart(ctxDesconfianza, {
        type: 'pie',
        data: {
            labels: {!! json_encode($desconfianzaPoliciaPorEdad->pluck('rango_edad')) !!},
            datasets: [{
                label: 'Personas que NO confían',
                data: {!! json_encode($desconfianzaPoliciaPorEdad->pluck('total')) !!},
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40',
                    '#FF6B9D'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif

    // Gráfico de calificación del servicio de seguridad
    @if(!$calificacionSeguridad->isEmpty())
    var ctxCalificacion = document.getElementById('calificacionSeguridadChart').getContext('2d');
    var calificacionChart = new Chart(ctxCalificacion, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($calificacionSeguridad->pluck('servicio_seguridad')) !!},
            datasets: [{
                label: 'Calificaciones',
                data: {!! json_encode($calificacionSeguridad->pluck('total')) !!},
                backgroundColor: [
                    '#28a745', // Verde para Excelente
                    '#17a2b8', // Azul para Muy buena
                    '#ffc107', // Amarillo para Buena
                    '#fd7e14', // Naranja para Regular
                    '#dc3545'  // Rojo para Mala
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif

    // Gráfico de horarios inseguros
    @if(!$horariosInseguros->isEmpty())
    var ctxHorarios = document.getElementById('horariosInsegurosChart').getContext('2d');
    var horariosChart = new Chart(ctxHorarios, {
        type: 'bar',
        data: {
            labels: {!! json_encode($horariosInseguros->pluck('horario_inseguro')) !!},
            datasets: [{
                label: 'Cantidad de respuestas',
                data: {!! json_encode($horariosInseguros->pluck('total')) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
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
    @endif
</script>
@stop
