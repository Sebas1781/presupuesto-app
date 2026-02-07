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
            <div class="small-box" style="background-color: #235B4E; color: #fff;">
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
            <div class="small-box" style="background-color: #10312B; color: #fff;">
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
            <div class="small-box" style="background-color: #BC955C; color: #fff;">
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
            <div class="small-box" style="background-color: #9F2441; color: #fff;">
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
                <div class="card-header" style="background-color: #235B4E; color: #fff;">
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
                                    <div class="small-box" style="background-color: #9F2441; color: #fff;">
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
                                    <div class="small-box" style="background-color: #BC955C; color: #fff;">
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
                                    <div class="small-box" style="background-color: #235B4E; color: #fff;">
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
                                    <div class="small-box" style="background-color: #10312B; color: #fff;">
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

    <!-- ANÁLISIS POR DISTRITO MOVIDO DESDE DASHBOARD -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #691C32; color: #fff;">
                    <h3 class="card-title text-white">
                        <i class="fas fa-chart-bar mr-2"></i>ANÁLISIS POR DISTRITO
                    </h3>
                </div>
                <div class="card-body">
                    <!-- BLOQUE A: Análisis Demográfico -->
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h4 class="mb-4" style="color: #4E232E;">
                                <i class="fas fa-users mr-2"></i>BLOQUE A: Análisis Demográfico
                            </h4>
                        </div>

                        <!-- Distrito 20 Demográfico -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #6F7271; color: #fff;">
                                    <h5 class="card-title text-white mb-0">Colonias del Distrito 20</h5>
                                    <small class="text-white-50">Género y Edad por Colonia</small>
                                </div>
                                <div class="card-body">
                                    <canvas id="distrito20DemograficoChart" height="300"></canvas>
                                    @if($distrito20Demograficos->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles para el Distrito 20</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Distrito 5 Demográfico -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #56242A; color: white;">
                                    <h5 class="card-title text-white mb-0">Colonias del Distrito 5</h5>
                                    <small class="text-white-50">Género y Edad por Colonia</small>
                                </div>
                                <div class="card-body">
                                    <canvas id="distrito5DemograficoChart" height="300"></canvas>
                                    @if($distrito5Demograficos->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles para el Distrito 5</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE A: Nivel Educativo -->
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h5 class="mb-4" style="color: #4E232E;">
                                <i class="fas fa-graduation-cap mr-2"></i>NIVEL EDUCATIVO DE LA POBLACIÓN GENERAL
                            </h5>
                        </div>

                        <!-- Gráfica General de Nivel Educativo -->
                        <div class="col-md-10 mx-auto">
                            <div class="card">
                                <div class="card-header" style="background-color: #9D2449; color: white;">
                                    <h5 class="card-title text-white mb-0">Distribución de Nivel Educativo</h5>
                                    <small class="text-white-50">Basado en todas las encuestas</small>
                                </div>
                                <div class="card-body" style="height: 500px; padding: 20px;">
                                    @if(!isset($nivelEducativoData) || $nivelEducativoData->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles de nivel educativo</p>
                                        <small class="text-info">Total encuestas: {{ $totalEncuestas ?? 0 }}</small>
                                    @else
                                        <canvas id="nivelEducativoChart" width="800" height="400"></canvas>
                                        <small class="text-success">Datos encontrados: {{ $nivelEducativoData->count() }} niveles educativos</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE A: Nuevas Gráficas Demográficas -->
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h5 class="mb-4" style="color: #4E232E;">
                                <i class="fas fa-chart-pie mr-2"></i>ANÁLISIS DEMOGRÁFICO COMPLEMENTARIO
                            </h5>
                        </div>

                        <!-- Distribución por Rango de Edad -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #4E232E; color: white;">
                                    <h5 class="card-title text-white mb-0">Distribución por Rango de Edad</h5>
                                    <small class="text-white-50">Todos los encuestados</small>
                                </div>
                                <div class="card-body">
                                    <canvas id="edadDistribucionChart" height="300"></canvas>
                                    @if($edadDistribucion->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Estado Civil -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #56242A; color: white;">
                                    <h5 class="card-title text-white mb-0">Estado Civil de la Población</h5>
                                    <small class="text-white-50">Todos los encuestados</small>
                                </div>
                                <div class="card-body">
                                    <canvas id="estadoCivilChart" height="300"></canvas>
                                    @if($estadoCivilDistribucion->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Género × Nivel Educativo -->
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header" style="background-color: #B3865D; color: white;">
                                    <h5 class="card-title text-white mb-0">Género por Nivel Educativo</h5>
                                    <small class="text-white-50">Relación entre género y escolaridad</small>
                                </div>
                                <div class="card-body" style="height: 450px;">
                                    <canvas id="generoEducacionChart"></canvas>
                                    @if($generoEducacion->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE B: Análisis de Obras -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="mb-4" style="color: #B3865D;">
                                <i class="fas fa-hammer mr-2"></i>BLOQUE B: Prioridad de Obras Públicas por Colonia
                            </h4>
                        </div>

                        <!-- Distrito 20 Obras -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #9D2449; color: white;">
                                    <h5 class="card-title text-white mb-0">Distrito 20</h5>
                                    <small class="text-white-50">Seleccione una colonia para ver las obras</small>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="colonia20Select">Seleccionar Colonia:</label>
                                        <select class="form-control" id="colonia20Select" onchange="cargarObrasColonia(this.value, 'distrito20ObrasChart', '#9D2449', '#4E232E')">
                                            <option value="">-- Seleccione una colonia --</option>
                                            @foreach($coloniasDistrito20 as $colonia)
                                                <option value="{{ $colonia->id }}">{{ $colonia->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="distrito20ObrasContainer" style="display: none;">
                                        <canvas id="distrito20ObrasChart" height="300"></canvas>
                                    </div>
                                    <div id="distrito20ObrasEmpty" class="text-muted text-center">
                                        <i class="fas fa-info-circle mr-1"></i>Seleccione una colonia para ver las obras
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Distrito 5 Obras -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #B3865D; color: white;">
                                    <h5 class="card-title text-white mb-0">Distrito 5</h5>
                                    <small class="text-white-50">Seleccione una colonia para ver las obras</small>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="colonia5Select">Seleccionar Colonia:</label>
                                        <select class="form-control" id="colonia5Select" onchange="cargarObrasColonia(this.value, 'distrito5ObrasChart', '#B3865D', '#56242A')">
                                            <option value="">-- Seleccione una colonia --</option>
                                            @foreach($coloniasDistrito5 as $colonia)
                                                <option value="{{ $colonia->id }}">{{ $colonia->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="distrito5ObrasContainer" style="display: none;">
                                        <canvas id="distrito5ObrasChart" height="300"></canvas>
                                    </div>
                                    <div id="distrito5ObrasEmpty" class="text-muted text-center">
                                        <i class="fas fa-info-circle mr-1"></i>Seleccione una colonia para ver las obras
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE B: Nuevas Gráficas de Obras y Reportes -->
                    <div class="row mt-5 mb-5">
                        <div class="col-md-12">
                            <h5 class="mb-4" style="color: #B3865D;">
                                <i class="fas fa-chart-bar mr-2"></i>ANÁLISIS GENERAL DE OBRAS Y REPORTES
                            </h5>
                        </div>

                        <!-- Top 10 Obras con Mayor Prioridad -->
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-header" style="background-color: #9D2449; color: white;">
                                    <h5 class="card-title text-white mb-0">Top 10 Obras con Mayor Prioridad</h5>
                                    <small class="text-white-50">Promedio general de todas las colonias</small>
                                </div>
                                <div class="card-body" style="height: 400px;">
                                    <canvas id="topObrasChart"></canvas>
                                    @if($topObrasGeneral->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Reportes por Tipo -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #4E232E; color: white;">
                                    <h5 class="card-title text-white mb-0">Distribución de Reportes por Tipo</h5>
                                    <small class="text-white-50">Tipos de violencia reportados</small>
                                </div>
                                <div class="card-body">
                                    <canvas id="reportesTipoChart" height="300"></canvas>
                                    @if($reportesPorTipo->isEmpty())
                                        <p class="text-muted text-center">No hay reportes registrados</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Colonias con Mayor Solicitud de Reportes -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #56242A; color: white;">
                                    <h5 class="card-title text-white mb-0">Colonias con Mayor Solicitud de Reportes</h5>
                                    <small class="text-white-50">Encuestados que desean reportar</small>
                                </div>
                                <div class="card-body">
                                    <canvas id="coloniasReportesChart" height="300"></canvas>
                                    @if($coloniasMasReportes->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE C: Nuevas Gráficas de Seguridad Complementarias -->
                    <div class="row mt-5 mb-5">
                        <div class="col-md-12">
                            <h4 class="mb-4" style="color: #9D2449;">
                                <i class="fas fa-shield-alt mr-2"></i>BLOQUE C: Análisis Detallado de Seguridad Pública
                            </h4>
                        </div>

                        <!-- Confianza en la Policía -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header" style="background-color: #9D2449; color: white;">
                                    <h5 class="card-title text-white mb-0">¿Confía en la Policía?</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="confianzaPoliciaChart" height="280"></canvas>
                                    @if($confianzaPoliciaGeneral->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Percepción de Seguridad en Lugares -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header" style="background-color: #4E232E; color: white;">
                                    <h5 class="card-title text-white mb-0">Percepción de Seguridad por Lugar</h5>
                                    <small class="text-white-50">Escala: 1 (Inseguro) - 4 (Seguro)</small>
                                </div>
                                <div class="card-body" style="height: 400px;">
                                    <canvas id="percepcionLugaresChart"></canvas>
                                    @if($percepcionLugares->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Problemas de Seguridad más Frecuentes -->
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header" style="background-color: #56242A; color: white;">
                                    <h5 class="card-title text-white mb-0">Problemas de Seguridad: Nivel de Preocupación</h5>
                                    <small class="text-white-50">Escala: 1 (No preocupa) - 4 (Preocupa mucho)</small>
                                </div>
                                <div class="card-body" style="height: 550px;">
                                    <canvas id="problemasChart"></canvas>
                                    @if($problemasSeguridad->isEmpty())
                                        <p class="text-muted text-center">No hay datos disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para generar PDF -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('admin.export.estadisticas-pdf') }}" class="btn btn-lg" style="background-color: #9D2449; color: white;">
                                <i class="fas fa-file-pdf mr-2"></i>Generar Reporte PDF con Todas las Gráficas
                            </a>
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
    // Paleta de colores Pantone corporativa
    const paletaColores = {
        pantone420: '#9D2449',    // Rojo corporativo principal
        pantone504: '#4E232E',    // Rojo oscuro
        pantone490: '#56242A',    // Rojo medio
        pantone465: '#B3865D'     // Beige/dorado
    };

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

    <!-- ANÁLISIS POR DISTRITO MOVIDO DESDE DASHBOARD -->

    // Variables globales para ambos distritos
    const generos = ['Masculino', 'Femenino', 'LGBTIITTQ'];
    const generosLabels = {
        'Masculino': 'Masculino',
        'Femenino': 'Femenino',
        'LGBTIITTQ': 'LGBTIQ+'
    };
    const coloresGenero = [paletaColores.pantone420, paletaColores.pantone504, paletaColores.pantone490];

    // BLOQUE A: Gráficos demográficos por distrito
    @if(!$distrito20Demograficos->isEmpty())
    // Distrito 20 - Demográfico
    var ctxD20Demo = document.getElementById('distrito20DemograficoChart').getContext('2d');

    // Preparar datos para Distrito 20
    const distrito20Data = {!! json_encode($distrito20Demograficos->groupBy('colonia')) !!};
    const colonias20 = Object.keys(distrito20Data);

    // Crear datasets por género
    const datasets20Demo = [];

    generos.forEach((genero, index) => {
        const data = colonias20.map(colonia => {
            return distrito20Data[colonia]
                .filter(item => item.genero === genero)
                .reduce((sum, item) => sum + item.total, 0);
        });

        datasets20Demo.push({
            label: generosLabels[genero] || genero,
            data: data,
            backgroundColor: coloresGenero[index],
            borderColor: coloresGenero[index],
            borderWidth: 1
        });
    });

    new Chart(ctxD20Demo, {
        type: 'bar',
        data: {
            labels: colonias20,
            datasets: datasets20Demo
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Personas'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Colonias'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Distribución por Género - Distrito 20'
                }
            }
        }
    });
    @endif

    @if(!$distrito5Demograficos->isEmpty())
    // Distrito 5 - Demográfico
    var ctxD5Demo = document.getElementById('distrito5DemograficoChart').getContext('2d');

    // Preparar datos para Distrito 5
    const distrito5Data = {!! json_encode($distrito5Demograficos->groupBy('colonia')) !!};
    const colonias5 = Object.keys(distrito5Data);

    // Crear datasets por género
    const datasets5Demo = [];
    generos.forEach((genero, index) => {
        const data = colonias5.map(colonia => {
            return distrito5Data[colonia]
                .filter(item => item.genero === genero)
                .reduce((sum, item) => sum + item.total, 0);
        });

        datasets5Demo.push({
            label: generosLabels[genero] || genero,
            data: data,
            backgroundColor: coloresGenero[index],
            borderColor: coloresGenero[index],
            borderWidth: 1
        });
    });

    new Chart(ctxD5Demo, {
        type: 'bar',
        data: {
            labels: colonias5,
            datasets: datasets5Demo
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Personas'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Colonias'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Distribución por Género - Distrito 5'
                }
            }
        }
    });
    @endif

    // BLOQUE A: Gráfico General de Nivel Educativo

    @if(isset($nivelEducativoData) && !$nivelEducativoData->isEmpty())
    // Gráfica General de Nivel Educativo
    var ctxEducacion = document.getElementById('nivelEducativoChart').getContext('2d');

    // Preparar datos de educación
    const educacionData = {!! json_encode($nivelEducativoData) !!};
    const nivelesEducativos = educacionData.map(item => item.nivel_educativo);
    const totalesEducacion = educacionData.map(item => parseInt(item.total));

    // Colores para niveles educativos (usando paleta Pantone)
    const coloresEducacion = ['#9D2449', '#4E232E', '#56242A', '#B3865D', '#A0745A', '#8B6B47', '#7A5F3A'];

    new Chart(ctxEducacion, {
        type: 'bar',
        data: {
            labels: nivelesEducativos,
            datasets: [{
                label: 'Número de Personas',
                data: totalesEducacion,
                backgroundColor: coloresEducacion.slice(0, nivelesEducativos.length),
                borderColor: coloresEducacion.slice(0, nivelesEducativos.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Personas'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nivel Educativo'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Distribución de Nivel Educativo en la Población'
                }
            }
        }
    });
    @endif

    // BLOQUE B: Función para cargar gráficos dinámicos de obras por colonia
    let chartInstances = {}; // Para almacenar las instancias de los gráficos

    // ============ NUEVAS GRÁFICAS BLOQUE A ============

    // Distribución por Rango de Edad
    @if(!$edadDistribucion->isEmpty())
    var ctxEdad = document.getElementById('edadDistribucionChart').getContext('2d');
    new Chart(ctxEdad, {
        type: 'bar',
        data: {
            labels: {!! json_encode($edadDistribucion->pluck('edad')) !!},
            datasets: [{
                label: 'Número de personas',
                data: {!! json_encode($edadDistribucion->pluck('total')) !!},
                backgroundColor: ['#9D2449', '#4E232E', '#56242A', '#B3865D', '#A0745A'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, title: { display: true, text: 'Distribución por Rango de Edad' } },
            scales: { y: { beginAtZero: true, title: { display: true, text: 'Personas' } } }
        }
    });
    @endif

    // Estado Civil
    @if(!$estadoCivilDistribucion->isEmpty())
    var ctxEstadoCivil = document.getElementById('estadoCivilChart').getContext('2d');
    new Chart(ctxEstadoCivil, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($estadoCivilDistribucion->pluck('estado_civil')) !!},
            datasets: [{
                data: {!! json_encode($estadoCivilDistribucion->pluck('total')) !!},
                backgroundColor: ['#9D2449', '#4E232E', '#56242A', '#B3865D', '#A0745A'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: 'Estado Civil' }
            }
        }
    });
    @endif

    // Género × Nivel Educativo (stacked bar)
    @if(!$generoEducacion->isEmpty())
    (function(){
        var ctxGE = document.getElementById('generoEducacionChart').getContext('2d');
        const geData = {!! json_encode($generoEducacion) !!};
        const niveles = [...new Set(geData.map(d => d.nivel_educativo))];
        const generosGE = [...new Set(geData.map(d => d.genero))];
        const generosGELabels = {'Masculino': 'Masculino', 'Femenino': 'Femenino', 'LGBTIITTQ': 'LGBTIQ+'};
        const coloresGE = {'Masculino': '#9D2449', 'Femenino': '#4E232E', 'LGBTIITTQ': '#B3865D'};

        const datasetsGE = generosGE.map(gen => ({
            label: generosGELabels[gen] || gen,
            data: niveles.map(niv => {
                const found = geData.find(d => d.genero === gen && d.nivel_educativo === niv);
                return found ? found.total : 0;
            }),
            backgroundColor: coloresGE[gen] || '#56242A'
        }));

        new Chart(ctxGE, {
            type: 'bar',
            data: { labels: niveles, datasets: datasetsGE },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' }, title: { display: true, text: 'Género por Nivel Educativo' } },
                scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, title: { display: true, text: 'Personas' } } }
            }
        });
    })();
    @endif

    // ============ NUEVAS GRÁFICAS BLOQUE B ============

    // Top 10 Obras con Mayor Prioridad
    @if(!$topObrasGeneral->isEmpty())
    (function(){
        var ctxTop = document.getElementById('topObrasChart').getContext('2d');
        const topData = {!! json_encode($topObrasGeneral) !!};
        new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: topData.map(d => d.obra.length > 40 ? d.obra.substring(0, 40) + '...' : d.obra),
                datasets: [{
                    label: 'Prioridad Promedio',
                    data: topData.map(d => d.prioridad_promedio),
                    backgroundColor: '#9D2449',
                    borderColor: '#4E232E',
                    borderWidth: 1
                }, {
                    label: 'Total Calificaciones',
                    data: topData.map(d => d.total_calificaciones),
                    backgroundColor: '#B3865D',
                    borderColor: '#56242A',
                    borderWidth: 1,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, indexAxis: 'y',
                plugins: { legend: { position: 'top' }, title: { display: true, text: 'Top 10 Obras Públicas con Mayor Prioridad' } },
                scales: {
                    x: { beginAtZero: true, max: 5, title: { display: true, text: 'Prioridad (1-5)' } },
                    x1: { display: false },
                    y1: { display: false }
                }
            }
        });
    })();
    @endif

    // Reportes por Tipo
    @if(!$reportesPorTipo->isEmpty())
    var ctxReportes = document.getElementById('reportesTipoChart').getContext('2d');
    new Chart(ctxReportes, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($reportesPorTipo->pluck('tipo_reporte')) !!},
            datasets: [{
                data: {!! json_encode($reportesPorTipo->pluck('total')) !!},
                backgroundColor: ['#9D2449', '#4E232E', '#56242A', '#B3865D', '#A0745A'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' }, title: { display: true, text: 'Tipos de Reportes' } }
        }
    });
    @endif

    // Colonias con Mayor Solicitud de Reportes
    @if(!$coloniasMasReportes->isEmpty())
    var ctxColRep = document.getElementById('coloniasReportesChart').getContext('2d');
    new Chart(ctxColRep, {
        type: 'bar',
        data: {
            labels: {!! json_encode($coloniasMasReportes->map(function($c) { return $c->colonia ? $c->colonia->nombre : 'Sin col.'; })) !!},
            datasets: [{
                label: 'Solicitudes de Reporte',
                data: {!! json_encode($coloniasMasReportes->pluck('total')) !!},
                backgroundColor: '#56242A',
                borderColor: '#4E232E',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, title: { display: true, text: 'Colonias con Más Solicitudes de Reporte' } },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Solicitudes' } },
                x: { ticks: { maxRotation: 45, minRotation: 30 } }
            }
        }
    });
    @endif

    // ============ NUEVAS GRÁFICAS BLOQUE C ============

    // Confianza en la Policía
    @if(!$confianzaPoliciaGeneral->isEmpty())
    var ctxConfianza = document.getElementById('confianzaPoliciaChart').getContext('2d');
    new Chart(ctxConfianza, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($confianzaPoliciaGeneral->pluck('confia_policia')) !!},
            datasets: [{
                data: {!! json_encode($confianzaPoliciaGeneral->pluck('total')) !!},
                backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: '¿Confía en la Policía?' }
            }
        }
    });
    @endif

    // Percepción de Seguridad por Lugar
    @if(!$percepcionLugares->isEmpty())
    (function(){
        var ctxLugares = document.getElementById('percepcionLugaresChart').getContext('2d');
        const lugaresData = {!! json_encode($percepcionLugares) !!};
        new Chart(ctxLugares, {
            type: 'radar',
            data: {
                labels: lugaresData.map(d => d.lugar),
                datasets: [{
                    label: 'Promedio de Seguridad (1-4)',
                    data: lugaresData.map(d => d.promedio),
                    backgroundColor: 'rgba(157, 36, 73, 0.2)',
                    borderColor: '#9D2449',
                    pointBackgroundColor: '#9D2449',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#9D2449',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' }, title: { display: true, text: 'Percepción de Seguridad por Lugar' } },
                scales: { r: { min: 0, max: 4, ticks: { stepSize: 1 } } }
            }
        });
    })();
    @endif

    // Problemas de Seguridad más Frecuentes
    @if(!$problemasSeguridad->isEmpty())
    (function(){
        var ctxProblemas = document.getElementById('problemasChart').getContext('2d');
        const probData = {!! json_encode($problemasSeguridad) !!};
        new Chart(ctxProblemas, {
            type: 'bar',
            data: {
                labels: probData.map(d => d.problema.length > 35 ? d.problema.substring(0, 35) + '...' : d.problema),
                datasets: [{
                    label: 'Nivel de Preocupación (Promedio)',
                    data: probData.map(d => d.promedio),
                    backgroundColor: probData.map((d, i) => {
                        if (d.promedio >= 3) return '#dc3545';
                        if (d.promedio >= 2.5) return '#fd7e14';
                        if (d.promedio >= 2) return '#ffc107';
                        return '#28a745';
                    }),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Problemas de Seguridad: Nivel de Preocupación Ciudadana' }
                },
                scales: {
                    x: { beginAtZero: true, max: 4, title: { display: true, text: '1=No preocupa → 4=Preocupa mucho' } }
                }
            }
        });
    })();
    @endif

    function cargarObrasColonia(coloniaId, chartId, backgroundColor, borderColor) {
        if (!coloniaId) {
            // Si no hay colonia seleccionada, ocultar el gráfico
            const containerId = chartId.replace('Chart', 'Container');
            const emptyId = chartId.replace('ObrasChart', 'ObrasEmpty');
            document.getElementById(containerId).style.display = 'none';
            document.getElementById(emptyId).style.display = 'block';

            // Destruir gráfico existente si existe
            if (chartInstances[chartId]) {
                chartInstances[chartId].destroy();
                delete chartInstances[chartId];
            }
            return;
        }

        // Mostrar indicador de carga
        const containerId = chartId.replace('Chart', 'Container');
        const emptyId = chartId.replace('ObrasChart', 'ObrasEmpty');
        document.getElementById(emptyId).innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Cargando obras...';

        // Realizar petición AJAX
        fetch(`/admin/obras-por-colonia/${coloniaId}`)
            .then(response => response.json())
            .then(data => {
                // Ocultar mensaje vacío y mostrar contenedor del gráfico
                document.getElementById(emptyId).style.display = 'none';
                document.getElementById(containerId).style.display = 'block';

                // Destruir gráfico existente si existe
                if (chartInstances[chartId]) {
                    chartInstances[chartId].destroy();
                }

                // Crear nuevo gráfico
                const ctx = document.getElementById(chartId).getContext('2d');

                if (data.length === 0) {
                    document.getElementById(containerId).style.display = 'none';
                    document.getElementById(emptyId).style.display = 'block';
                    document.getElementById(emptyId).innerHTML = '<i class="fas fa-info-circle mr-1"></i>No hay datos de obras para esta colonia';
                    return;
                }

                chartInstances[chartId] = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(obra => obra.obra),
                        datasets: [{
                            label: 'Prioridad Promedio',
                            data: data.map(obra => obra.prioridad_promedio),
                            backgroundColor: backgroundColor,
                            borderColor: borderColor,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 5,
                                title: {
                                    display: true,
                                    text: 'Prioridad (1-5)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Obras Públicas'
                                },
                                ticks: {
                                    maxRotation: 45
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Prioridad de Obras por Colonia'
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error cargando obras:', error);
                document.getElementById(emptyId).style.display = 'block';
                document.getElementById(emptyId).innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Error al cargar las obras';
                document.getElementById(containerId).style.display = 'none';
            });
    }
</script>
@stop
