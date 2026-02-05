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
                        <button onclick="generarPdfEstadisticas()" class="btn btn-danger btn-block mb-2">
                            <i class="fas fa-file-pdf"></i> Generar PDF Estadísticas
                        </button>
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

    <!-- Estadísticas generales -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #B3865D; color: white;">
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

    <!-- Estadísticas por Distrito -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #9D2449; color: white;">
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
                                <div class="card-header" style="background-color: #4E232E; color: white;">
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

                    <!-- BLOQUE B: Análisis de Obras -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="mb-4" style="color: #B3865D;">
                                <i class="fas fa-hammer mr-2"></i>BLOQUE B: Prioridad de Obras Públicas
                            </h4>
                        </div>

                        <!-- Distrito 20 Obras -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #9D2449; color: white;">
                                    <h5 class="card-title text-white mb-0">Distrito 20</h5>
                                    <small class="text-white-50">Prioridad de Obras por Colonia</small>
                                </div>
                                <div class="card-body">
                                    <canvas id="distrito20ObrasChart" height="300"></canvas>
                                    @if($distrito20Obras->isEmpty())
                                        <p class="text-muted text-center">No hay datos de obras disponibles para el Distrito 20</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Distrito 5 Obras -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #B3865D; color: white;">
                                    <h5 class="card-title text-white mb-0">Distrito 5</h5>
                                    <small class="text-white-50">Prioridad de Obras por Colonia</small>
                                </div>
                                <div class="card-body">
                                    <canvas id="distrito5ObrasChart" height="300"></canvas>
                                    @if($distrito5Obras->isEmpty())
                                        <p class="text-muted text-center">No hay datos de obras disponibles para el Distrito 5</p>
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

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Paleta de colores Pantone
    const paletaColores = {
        pantone420: '#9D2449',
        pantone504: '#4E232E',
        pantone490: '#56242A',
        pantone465: '#B3865D'
    };

    // Función para generar PDF de estadísticas
    function generarPdfEstadisticas() {
        window.open('{{ route('admin.export.estadisticas-pdf') }}', '_blank');
    }

    // Gráfico de participación por colonia
    var ctx = document.getElementById('coloniaChart').getContext('2d');
    var coloniaChart = new Chart(ctx, {
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
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Variables globales para ambos distritos
    const generos = ['Masculino', 'Femenino', 'Otro'];
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
            label: genero,
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
            label: genero,
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

    // BLOQUE B: Gráficos de prioridad de obras
    @if(!$distrito20Obras->isEmpty())
    // Distrito 20 - Obras
    var ctxD20Obras = document.getElementById('distrito20ObrasChart').getContext('2d');
    const obras20 = {!! json_encode($distrito20Obras) !!};

    new Chart(ctxD20Obras, {
        type: 'bar',
        data: {
            labels: obras20.map(obra => obra.obra),
            datasets: [{
                label: 'Prioridad Promedio',
                data: obras20.map(obra => obra.prioridad_promedio),
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
                    text: 'Prioridad de Obras - Distrito 20'
                }
            }
        }
    });
    @endif

    @if(!$distrito5Obras->isEmpty())
    // Distrito 5 - Obras
    var ctxD5Obras = document.getElementById('distrito5ObrasChart').getContext('2d');
    const obras5 = {!! json_encode($distrito5Obras) !!};

    new Chart(ctxD5Obras, {
        type: 'bar',
        data: {
            labels: obras5.map(obra => obra.obra),
            datasets: [{
                label: 'Prioridad Promedio',
                data: obras5.map(obra => obra.prioridad_promedio),
                backgroundColor: paletaColores.pantone465,
                borderColor: paletaColores.pantone490,
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
                    text: 'Prioridad de Obras - Distrito 5'
                }
            }
        }
    });
    @endif
</script>
@stop
