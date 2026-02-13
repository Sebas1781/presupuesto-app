<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Encuesta;
use App\Models\Propuesta;
use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEncuestas = Encuesta::count();
        $totalPropuestas = Propuesta::count();
        $totalReportes = Reporte::count();

        $encuestasPorColonia = Encuesta::with('colonia')
            ->selectRaw('colonia_id, count(*) as total')
            ->groupBy('colonia_id')
            ->get();

        $encuestasRecientes = Encuesta::with(['colonia', 'propuestas', 'reportes'])
            ->latest()
            ->take(10)
            ->get();

        // Estadísticas de Seguridad Pública
        // 1. Rangos de edades de personas que NO confían en la policía
        $desconfianzaPoliciaPorEdad = Encuesta::where('confia_policia', 'No')
            ->selectRaw("
                CASE
                    WHEN edad = 'De 18 a 24 años' THEN '18-24'
                    WHEN edad = 'De 25 a 34 años' THEN '25-34'
                    WHEN edad = 'De 35 a 44 años' THEN '35-44'
                    WHEN edad = 'De 45 a 54 años' THEN '45-54'
                    WHEN edad = 'De 55 a 64 años' THEN '55-64'
                    WHEN edad = '65 años o más' THEN '65+'
                    ELSE 'Otro'
                END as rango_edad,
                COUNT(*) as total
            ")
            ->groupBy('rango_edad')
            ->get();

        // 2. Calificación del servicio de seguridad
        $calificacionSeguridad = Encuesta::whereNotNull('servicio_seguridad')
            ->selectRaw('servicio_seguridad, COUNT(*) as total')
            ->groupBy('servicio_seguridad')
            ->get();

        // 3. Horarios de mayor inseguridad
        $horariosInseguros = Encuesta::whereNotNull('horario_inseguro')
            ->selectRaw('horario_inseguro, COUNT(*) as total')
            ->groupBy('horario_inseguro')
            ->get();

        // 4. Promedio de escalas de seguridad
        $promediosSeguridad = Encuesta::selectRaw('
            AVG(emergencia_transporte) as avg_emergencia,
            AVG(caminar_noche) as avg_caminar,
            AVG(hijos_solos) as avg_hijos,
            AVG(transporte_publico) as avg_transporte
        ')
        ->whereNotNull('emergencia_transporte')
        ->first();

        // NUEVAS ESTADÍSTICAS POR DISTRITO
        // BLOQUE A: Análisis demográfico por distrito
        // Obtener colonias por distrito (asumiendo que tienes un campo distrito en colonias)
        $distrito20Demograficos = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', 20) // Asumiendo campo distrito
            ->selectRaw('
                colonias.nombre as colonia,
                encuestas.genero,
                encuestas.edad,
                COUNT(*) as total
            ')
            ->groupBy('colonias.nombre', 'encuestas.genero', 'encuestas.edad')
            ->get();

        $distrito5Demograficos = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', 5)
            ->selectRaw('
                colonias.nombre as colonia,
                encuestas.genero,
                encuestas.edad,
                COUNT(*) as total
            ')
            ->groupBy('colonias.nombre', 'encuestas.genero', 'encuestas.edad')
            ->get();

        // Datos de Nivel Educativo por Distrito
        $distrito20EducacionData = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', 20)
            ->selectRaw('
                colonias.nombre as colonia,
                encuestas.nivel_educativo,
                COUNT(*) as total
            ')
            ->groupBy('colonias.nombre', 'encuestas.nivel_educativo')
            ->get();

        $distrito5EducacionData = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', 5)
            ->selectRaw('
                colonias.nombre as colonia,
                encuestas.nivel_educativo,
                COUNT(*) as total
            ')
            ->groupBy('colonias.nombre', 'encuestas.nivel_educativo')
            ->get();

        // Pre-cargar todas las obras en 1 sola query (evita N+1)
        $allObras = \App\Models\ObraPublica::all()->keyBy('id');
        $coloniaIdsDistrito20 = \App\Models\Colonia::where('distrito', 20)->pluck('id');
        $coloniaIdsDistrito5 = \App\Models\Colonia::where('distrito', 5)->pluck('id');

        // BLOQUE B: Análisis de prioridad de obras por distrito (optimizado)
        $distrito20Obras = Encuesta::with('colonia')
            ->whereIn('colonia_id', $coloniaIdsDistrito20)
            ->whereNotNull('obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) use ($allObras) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = $allObras->get($obraId);
                        if ($obra) {
                            $obras[] = [
                                'obra' => $obra->nombre,
                                'prioridad' => $prioridad,
                                'colonia' => $encuesta->colonia->nombre
                            ];
                        }
                    }
                }
                return $obras;
            })
            ->groupBy('obra')
            ->map(function($group) {
                return [
                    'obra' => $group->first()['obra'],
                    'prioridad_promedio' => round($group->avg('prioridad'), 1),
                    'total_respuestas' => $group->count()
                ];
            })
            ->values();

        $distrito5Obras = Encuesta::with('colonia')
            ->whereIn('colonia_id', $coloniaIdsDistrito5)
            ->whereNotNull('obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) use ($allObras) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = $allObras->get($obraId);
                        if ($obra) {
                            $obras[] = [
                                'obra' => $obra->nombre,
                                'prioridad' => $prioridad,
                                'colonia' => $encuesta->colonia->nombre
                            ];
                        }
                    }
                }
                return $obras;
            })
            ->groupBy('obra')
            ->map(function($group) {
                return [
                    'obra' => $group->first()['obra'],
                    'colonia_nombre' => $group->first()['colonia'],
                    'prioridad_promedio' => round($group->avg('prioridad'), 1),
                    'total_calificaciones' => $group->count()
                ];
            })
            ->values();

        return view('admin.dashboard', compact(
            'totalEncuestas',
            'totalPropuestas',
            'totalReportes',
            'encuestasPorColonia',
            'encuestasRecientes',
            'desconfianzaPoliciaPorEdad',
            'calificacionSeguridad',
            'horariosInseguros',
            'promediosSeguridad',
            'distrito20Demograficos',
            'distrito5Demograficos',
            'distrito20EducacionData',
            'distrito5EducacionData',
            'distrito20Obras',
            'distrito5Obras'
        ));
    }

    public function estadisticas()
    {
        $totalEncuestas = Encuesta::count();
        $totalPropuestas = Propuesta::count();
        $totalReportes = Reporte::count();

        $encuestasPorColonia = Encuesta::with('colonia')
            ->selectRaw('colonia_id, count(*) as total')
            ->groupBy('colonia_id')
            ->get();

        // Estadísticas de Seguridad Pública
        // 1. Rangos de edades de personas que NO confían en la policía
        $desconfianzaPoliciaPorEdad = Encuesta::where('confia_policia', 'No')
            ->selectRaw("
                CASE
                    WHEN edad = 'De 18 a 24 años' THEN '18-24'
                    WHEN edad = 'De 25 a 34 años' THEN '25-34'
                    WHEN edad = 'De 35 a 44 años' THEN '35-44'
                    WHEN edad = 'De 45 a 54 años' THEN '45-54'
                    WHEN edad = 'De 55 a 64 años' THEN '55-64'
                    WHEN edad = '65 años o más' THEN '65+'
                    ELSE 'Otro'
                END as rango_edad,
                COUNT(*) as total
            ")
            ->groupBy('rango_edad')
            ->get();

        // 2. Calificación del servicio de seguridad
        $calificacionSeguridad = Encuesta::whereNotNull('servicio_seguridad')
            ->selectRaw('servicio_seguridad, COUNT(*) as total')
            ->groupBy('servicio_seguridad')
            ->get();

        // 3. Horarios de mayor inseguridad
        $horariosInseguros = Encuesta::whereNotNull('horario_inseguro')
            ->selectRaw('horario_inseguro, COUNT(*) as total')
            ->groupBy('horario_inseguro')
            ->get();

        // 4. Promedio de escalas de seguridad
        $promediosSeguridad = Encuesta::selectRaw('
            AVG(emergencia_transporte) as avg_emergencia,
            AVG(caminar_noche) as avg_caminar,
            AVG(hijos_solos) as avg_hijos,
            AVG(transporte_publico) as avg_transporte
        ')
        ->whereNotNull('emergencia_transporte')
        ->first();

        // ESTADÍSTICAS POR DISTRITO
        // Obtener colonias por distrito para los dropdowns
        $coloniasDistrito20 = \App\Models\Colonia::where('distrito', '20')->orderBy('nombre')->get();
        $coloniasDistrito5 = \App\Models\Colonia::where('distrito', '5')->orderBy('nombre')->get();

        // BLOQUE A: Análisis demográfico por distrito
        $distrito20Demograficos = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', '20')
            ->selectRaw('
                colonias.nombre as colonia,
                encuestas.genero,
                encuestas.edad,
                COUNT(*) as total
            ')
            ->groupBy('colonias.nombre', 'encuestas.genero', 'encuestas.edad')
            ->get();

        $distrito5Demograficos = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', '5')
            ->selectRaw('
                colonias.nombre as colonia,
                encuestas.genero,
                encuestas.edad,
                COUNT(*) as total
            ')
            ->groupBy('colonias.nombre', 'encuestas.genero', 'encuestas.edad')
            ->get();

        // Datos de Nivel Educativo por Distrito
        $distrito20EducacionData = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', 20)
            ->selectRaw('
                colonias.nombre as colonia,
                encuestas.nivel_educativo,
                COUNT(*) as total
            ')
            ->groupBy('colonias.nombre', 'encuestas.nivel_educativo')
            ->get();

        $distrito5EducacionData = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', 5)
            ->selectRaw('
                colonias.nombre as colonia,
                encuestas.nivel_educativo,
                COUNT(*) as total
            ')
            ->groupBy('colonias.nombre', 'encuestas.nivel_educativo')
            ->get();

        // Pre-cargar todas las obras en 1 sola query (evita N+1)
        $allObras = \App\Models\ObraPublica::all()->keyBy('id');

        // BLOQUE B: Análisis de prioridad de obras por distrito (optimizado)
        $distrito20Obras = Encuesta::with('colonia')
            ->whereIn('colonia_id', $coloniasDistrito20->pluck('id'))
            ->whereNotNull('obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) use ($allObras) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = $allObras->get($obraId);
                        if ($obra) {
                            $obras[] = [
                                'obra' => $obra->nombre,
                                'prioridad' => $prioridad,
                                'colonia' => $encuesta->colonia->nombre
                            ];
                        }
                    }
                }
                return $obras;
            })
            ->groupBy('obra')
            ->map(function($group) {
                return [
                    'obra' => $group->first()['obra'],
                    'prioridad_promedio' => round($group->avg('prioridad'), 1),
                    'total_respuestas' => $group->count()
                ];
            })
            ->values();

        $distrito5Obras = Encuesta::with('colonia')
            ->whereIn('colonia_id', $coloniasDistrito5->pluck('id'))
            ->whereNotNull('obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) use ($allObras) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = $allObras->get($obraId);
                        if ($obra) {
                            $obras[] = [
                                'obra' => $obra->nombre,
                                'prioridad' => $prioridad,
                                'colonia' => $encuesta->colonia->nombre
                            ];
                        }
                    }
                }
                return $obras;
            })
            ->groupBy('obra')
            ->map(function($group) {
                return [
                    'obra' => $group->first()['obra'],
                    'colonia_nombre' => $group->first()['colonia'],
                    'prioridad_promedio' => round($group->avg('prioridad'), 1),
                    'total_calificaciones' => $group->count()
                ];
            })
            ->values();

        // DATOS REALES: Nivel Educativo General (todas las encuestas)
        $nivelEducativoData = Encuesta::whereNotNull('nivel_educativo')
            ->where('nivel_educativo', '!=', '')
            ->selectRaw('
                nivel_educativo,
                COUNT(*) as total
            ')
            ->groupBy('nivel_educativo')
            ->get();

        // ============ NUEVAS GRÁFICAS BLOQUE A ============

        // Distribución por Rango de Edad (general)
        $edadDistribucion = Encuesta::whereNotNull('edad')
            ->where('edad', '!=', '')
            ->selectRaw('edad, COUNT(*) as total')
            ->groupBy('edad')
            ->orderByRaw("CASE
                WHEN edad = 'De 18 a 24 años' THEN 1
                WHEN edad = 'De 25 a 34 años' THEN 2
                WHEN edad = 'De 35 a 49 años' THEN 3
                WHEN edad = 'De 50 a 59 años' THEN 4
                WHEN edad = 'Más de 60 años' THEN 5
                ELSE 6
            END")
            ->get();

        // Estado Civil de la Población
        $estadoCivilDistribucion = Encuesta::whereNotNull('estado_civil')
            ->where('estado_civil', '!=', '')
            ->selectRaw('estado_civil, COUNT(*) as total')
            ->groupBy('estado_civil')
            ->orderBy('total', 'desc')
            ->get();

        // Género × Nivel Educativo (cross-tabulation)
        $generoEducacion = Encuesta::whereNotNull('genero')
            ->whereNotNull('nivel_educativo')
            ->where('nivel_educativo', '!=', '')
            ->selectRaw('genero, nivel_educativo, COUNT(*) as total')
            ->groupBy('genero', 'nivel_educativo')
            ->get();

        // ============ NUEVAS GRÁFICAS BLOQUE B ============

        // Top Obras con Mayor Prioridad (general, todas las colonias) - optimizado con Score Bayesiano
        $obrasData = Encuesta::whereNotNull('obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) use ($allObras) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = $allObras->get($obraId);
                        if ($obra) {
                            $obras[] = [
                                'obra' => $obra->nombre,
                                'prioridad' => $prioridad,
                            ];
                        }
                    }
                }
                return $obras;
            })
            ->groupBy('obra')
            ->map(function($group) {
                return [
                    'obra' => $group->first()['obra'],
                    'prioridad_promedio' => $group->avg('prioridad'),
                    'total_calificaciones' => $group->count()
                ];
            });

        // Calcular promedio global y umbral mínimo de votos
        $promedioGlobal = $obrasData->avg('prioridad_promedio');
        $votosOrdenados = $obrasData->pluck('total_calificaciones')->sort()->values();
        $minimoVotos = $votosOrdenados->count() > 0 
            ? $votosOrdenados[floor($votosOrdenados->count() * 0.25)] // Percentil 25
            : 1;

        // Aplicar Score Bayesiano (Weighted Rating como IMDB)
        // Score = (C × m + R × v) / (C + v)
        // Donde: R=promedio obra, v=votos obra, m=promedio global, C=mínimo votos
        $topObrasGeneral = $obrasData->map(function($item) use ($promedioGlobal, $minimoVotos) {
            $R = $item['prioridad_promedio'];
            $v = $item['total_calificaciones'];
            $m = $promedioGlobal;
            $C = $minimoVotos;
            
            // Fórmula del Score Bayesiano
            $score = ($C * $m + $R * $v) / ($C + $v);
            
            return [
                'obra' => $item['obra'],
                'prioridad_promedio' => round($item['prioridad_promedio'], 1),
                'total_calificaciones' => $item['total_calificaciones'],
                'score_bayesiano' => round($score, 2)
            ];
        })
        ->sortByDesc('score_bayesiano') // Ordenar por el score ponderado
        ->take(10)
        ->values();

        // Distribución de Reportes por Tipo
        $reportesPorTipo = Reporte::whereNotNull('tipo_reporte')
            ->where('tipo_reporte', '!=', '')
            ->selectRaw('tipo_reporte, COUNT(*) as total')
            ->groupBy('tipo_reporte')
            ->orderBy('total', 'desc')
            ->get();

        // Colonias con Mayor Solicitud de Reportes
        $coloniasMasReportes = Encuesta::with('colonia')
            ->where('desea_reporte', true)
            ->selectRaw('colonia_id, COUNT(*) as total')
            ->groupBy('colonia_id')
            ->orderBy('total', 'desc')
            ->get();

        // ============ NUEVAS GRÁFICAS BLOQUE C ============

        // Confianza en la Policía (Sí/No general)
        $confianzaPoliciaGeneral = Encuesta::whereNotNull('confia_policia')
            ->where('confia_policia', '!=', '')
            ->selectRaw('confia_policia, COUNT(*) as total')
            ->groupBy('confia_policia')
            ->get();

        // Problemas de Seguridad más Frecuentes (from JSON)
        $problemasSeguridadRaw = Encuesta::whereNotNull('problemas_seguridad')
            ->pluck('problemas_seguridad');

        $problemasLabels = [
            'Corrupción de los elementos de seguridad',
            'Robo a casa habitación',
            'Asaltos a transeúntes',
            'Robo de vehículos, motos o autopartes',
            'Extorsión por llamada telefónica',
            'Venta de sustancias ilícitas (drogas)',
            'Falta de vigilancia y presencia de policías',
            'Venta y/o consumo de alcohol en la calle',
            'Violencia familiar',
            'Violencia contra adultos mayores',
            'Violencia contra animales',
            'Violencia contra personas discapacitadas',
            'Bullying en las escuelas',
            'Acoso a mujeres en la calle',
            'Discriminación a comunidad LGBTIQ+',
            'Riñas entre vecinos',
            'Consumo de drogas en la calle'
        ];

        $problemasPromedios = [];
        foreach ($problemasSeguridadRaw as $json) {
            $problemas = is_array($json) ? $json : json_decode($json, true);
            if (is_array($problemas)) {
                foreach ($problemas as $index => $valor) {
                    if (!isset($problemasPromedios[$index])) {
                        $problemasPromedios[$index] = ['suma' => 0, 'count' => 0];
                    }
                    $problemasPromedios[$index]['suma'] += intval($valor);
                    $problemasPromedios[$index]['count']++;
                }
            }
        }

        $problemasSeguridad = collect($problemasPromedios)->map(function($item, $index) use ($problemasLabels) {
            return [
                'problema' => $problemasLabels[$index] ?? "Problema " . ($index + 1),
                'promedio' => $item['count'] > 0 ? round($item['suma'] / $item['count'], 2) : 0,
                'total_respuestas' => $item['count']
            ];
        })->sortByDesc('promedio')->values();

        // Percepción de Seguridad en Lugares (from JSON)
        $lugaresRaw = Encuesta::whereNotNull('lugares_seguros')
            ->pluck('lugares_seguros');

        $lugaresLabels = [
            'Un parque de su comunidad',
            'En el mercado o tianguis',
            'Plaza comercial o supermercado',
            'En un cajero automático',
            'Transporte público',
            'Al exterior de una escuela',
            'Calles cercanas a su domicilio',
            'Municipio de Tecámac'
        ];

        $lugaresPromedios = [];
        foreach ($lugaresRaw as $json) {
            $lugares = is_array($json) ? $json : json_decode($json, true);
            if (is_array($lugares)) {
                foreach ($lugares as $index => $valor) {
                    if (!isset($lugaresPromedios[$index])) {
                        $lugaresPromedios[$index] = ['suma' => 0, 'count' => 0];
                    }
                    $lugaresPromedios[$index]['suma'] += intval($valor);
                    $lugaresPromedios[$index]['count']++;
                }
            }
        }

        $percepcionLugares = collect($lugaresPromedios)->map(function($item, $index) use ($lugaresLabels) {
            return [
                'lugar' => $lugaresLabels[$index] ?? "Lugar " . ($index + 1),
                'promedio' => $item['count'] > 0 ? round($item['suma'] / $item['count'], 2) : 0,
                'total_respuestas' => $item['count']
            ];
        })->sortByDesc('promedio')->values();

        return view('admin.estadisticas', compact(
            'totalEncuestas',
            'totalPropuestas',
            'totalReportes',
            'encuestasPorColonia',
            'desconfianzaPoliciaPorEdad',
            'calificacionSeguridad',
            'horariosInseguros',
            'promediosSeguridad',
            'distrito20Demograficos',
            'distrito5Demograficos',
            'nivelEducativoData',
            'distrito20Obras',
            'distrito5Obras',
            'coloniasDistrito20',
            'coloniasDistrito5',
            'edadDistribucion',
            'estadoCivilDistribucion',
            'generoEducacion',
            'topObrasGeneral',
            'reportesPorTipo',
            'coloniasMasReportes',
            'confianzaPoliciaGeneral',
            'problemasSeguridad',
            'percepcionLugares'
        ));
    }

    public function encuestas(Request $request)
    {
        $query = Encuesta::with(['colonia', 'propuestas', 'reportes']);

        // Filtros
        if ($request->has('colonia_id') && $request->colonia_id) {
            $query->where('colonia_id', $request->colonia_id);
        }

        if ($request->has('genero') && $request->genero) {
            $query->where('genero', $request->genero);
        }

        if ($request->has('fecha_desde') && $request->fecha_desde) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta') && $request->fecha_hasta) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $encuestas = $query->latest()->paginate(20);
        $colonias = \App\Models\Colonia::all();

        return view('admin.encuestas.index', compact('encuestas', 'colonias'));
    }

    public function showEncuesta($id)
    {
        $encuesta = Encuesta::with(['colonia', 'propuestas', 'reportes'])->findOrFail($id);

        // Obtener nombres de obras para las calificaciones (optimizado)
        $allObras = \App\Models\ObraPublica::all()->keyBy('id');
        $obrasCalificadasConNombres = [];
        if ($encuesta->obras_calificadas) {
            foreach ($encuesta->obras_calificadas as $obraId => $calificacion) {
                $obra = $allObras->get($obraId);
                $obrasCalificadasConNombres[] = [
                    'id' => $obraId,
                    'nombre' => $obra ? $obra->nombre : 'Obra no encontrada',
                    'calificacion' => $calificacion
                ];
            }
        }

        return view('admin.encuestas.show', compact('encuesta', 'obrasCalificadasConNombres'));
    }

    public function getDashboardData()
    {
        $totalEncuestas = Encuesta::count();
        $totalPropuestas = Propuesta::count();
        $totalReportes = Reporte::count();

        $encuestasPorColonia = Encuesta::with('colonia')
            ->selectRaw('colonia_id, count(*) as total')
            ->groupBy('colonia_id')
            ->get();

        // Estadísticas de Seguridad Pública
        $seguridadStats = [];
        $camposSeguridad = [
            'emergencia_transporte' => 'Emergencia de Transporte',
            'caminar_noche' => 'Caminar de Noche',
            'hijos_solos' => 'Hijos Solos',
            'transporte_publico' => 'Transporte Público'
        ];

        foreach ($camposSeguridad as $campo => $nombre) {
            $promedio = Encuesta::whereNotNull($campo)->avg($campo);
            if ($promedio) {
                $seguridadStats[$campo] = $promedio;
            }
        }

        // Distrito 20 - Demográfico
        $distrito20Demograficos = \DB::select("
            SELECT
                c.nombre as colonia,
                e.genero,
                COALESCE(e.edad, 'No especificado') as rango_edad,
                COUNT(*) as total
            FROM encuestas e
            JOIN colonias c ON e.colonia_id = c.id
            WHERE c.distrito = 20
            GROUP BY c.nombre, e.genero, rango_edad
            ORDER BY c.nombre, e.genero, rango_edad
        ");

        // Distrito 5 - Demográfico
        $distrito5Demograficos = \DB::select("
            SELECT
                c.nombre as colonia,
                e.genero,
                COALESCE(e.edad, 'No especificado') as rango_edad,
                COUNT(*) as total
            FROM encuestas e
            JOIN colonias c ON e.colonia_id = c.id
            WHERE c.distrito = 5
            GROUP BY c.nombre, e.genero, rango_edad
            ORDER BY c.nombre, e.genero, rango_edad
        ");

        // Distrito 20 - Obras
        $distrito20Obras = \DB::select("
            SELECT
                c.nombre as colonia_nombre,
                op.nombre as obra,
                AVG(CAST(JSON_EXTRACT(e.obras_calificadas, CONCAT('$.\"', op.id, '\"')) AS UNSIGNED)) as prioridad_promedio,
                COUNT(*) as total_calificaciones
            FROM encuestas e
            JOIN colonias c ON e.colonia_id = c.id
            JOIN obras_publicas op ON op.colonia_id = c.id
            WHERE c.distrito = 20
            AND JSON_EXTRACT(e.obras_calificadas, CONCAT('$.\"', op.id, '\"')) IS NOT NULL
            GROUP BY c.nombre, op.id, op.nombre
            ORDER BY c.nombre, prioridad_promedio DESC
        ");

        // Distrito 5 - Obras
        $distrito5Obras = \DB::select("
            SELECT
                c.nombre as colonia_nombre,
                op.nombre as obra,
                AVG(CAST(JSON_EXTRACT(e.obras_calificadas, CONCAT('$.\"', op.id, '\"')) AS UNSIGNED)) as prioridad_promedio,
                COUNT(*) as total_calificaciones
            FROM encuestas e
            JOIN colonias c ON e.colonia_id = c.id
            JOIN obras_publicas op ON op.colonia_id = c.id
            WHERE c.distrito = 5
            AND JSON_EXTRACT(e.obras_calificadas, CONCAT('$.\"', op.id, '\"')) IS NOT NULL
            GROUP BY c.nombre, op.id, op.nombre
            ORDER BY c.nombre, prioridad_promedio DESC
        ");

        // Distrito 20 - Nivel Educativo
        $distrito20EducacionData = \DB::select("
            SELECT
                c.nombre as colonia,
                e.nivel_educativo,
                COUNT(*) as total
            FROM encuestas e
            JOIN colonias c ON e.colonia_id = c.id
            WHERE c.distrito = 20
            AND e.nivel_educativo IS NOT NULL
            GROUP BY c.nombre, e.nivel_educativo
            ORDER BY c.nombre, e.nivel_educativo
        ");

        // Distrito 5 - Nivel Educativo
        $distrito5EducacionData = \DB::select("
            SELECT
                c.nombre as colonia,
                e.nivel_educativo,
                COUNT(*) as total
            FROM encuestas e
            JOIN colonias c ON e.colonia_id = c.id
            WHERE c.distrito = 5
            AND e.nivel_educativo IS NOT NULL
            GROUP BY c.nombre, e.nivel_educativo
            ORDER BY c.nombre, e.nivel_educativo
        ");

        // Nivel Educativo General
        $nivelEducativoData = \DB::select("
            SELECT
                nivel_educativo,
                COUNT(*) as total
            FROM encuestas
            WHERE nivel_educativo IS NOT NULL
            AND nivel_educativo != ''
            GROUP BY nivel_educativo
            ORDER BY total DESC
        ");

        // ============ NUEVAS GRÁFICAS BLOQUE A ============
        $edadDistribucion = \DB::select("
            SELECT edad, COUNT(*) as total FROM encuestas
            WHERE edad IS NOT NULL AND edad != ''
            GROUP BY edad
            ORDER BY CASE
                WHEN edad = 'De 18 a 24 años' THEN 1
                WHEN edad = 'De 25 a 34 años' THEN 2
                WHEN edad = 'De 35 a 49 años' THEN 3
                WHEN edad = 'De 50 a 59 años' THEN 4
                WHEN edad = 'Más de 60 años' THEN 5
                ELSE 6
            END
        ");

        $estadoCivilDistribucion = \DB::select("
            SELECT estado_civil, COUNT(*) as total FROM encuestas
            WHERE estado_civil IS NOT NULL AND estado_civil != ''
            GROUP BY estado_civil ORDER BY total DESC
        ");

        $generoEducacion = \DB::select("
            SELECT genero, nivel_educativo, COUNT(*) as total FROM encuestas
            WHERE genero IS NOT NULL AND nivel_educativo IS NOT NULL AND nivel_educativo != ''
            GROUP BY genero, nivel_educativo
        ");

        // ============ NUEVAS GRÁFICAS BLOQUE B ============
        $reportesPorTipo = \DB::select("
            SELECT tipo_reporte, COUNT(*) as total FROM reportes
            WHERE tipo_reporte IS NOT NULL AND tipo_reporte != ''
            GROUP BY tipo_reporte ORDER BY total DESC
        ");

        $coloniasMasReportes = \DB::select("
            SELECT c.nombre as colonia, COUNT(*) as total
            FROM encuestas e JOIN colonias c ON e.colonia_id = c.id
            WHERE e.desea_reporte = 1
            GROUP BY c.nombre ORDER BY total DESC
        ");

        // Top Obras con Mayor Prioridad (general) - optimizado con Score Bayesiano
        $allObras = \App\Models\ObraPublica::all()->keyBy('id');
        $obrasDataExport = Encuesta::whereNotNull('obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) use ($allObras) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = $allObras->get($obraId);
                        if ($obra) {
                            $obras[] = ['obra' => $obra->nombre, 'prioridad' => $prioridad];
                        }
                    }
                }
                return $obras;
            })
            ->groupBy('obra')
            ->map(function($group) {
                return [
                    'obra' => $group->first()['obra'],
                    'prioridad_promedio' => $group->avg('prioridad'),
                    'total_calificaciones' => $group->count()
                ];
            });

        // Calcular promedio global y umbral mínimo de votos
        $promedioGlobalExport = $obrasDataExport->avg('prioridad_promedio');
        $votosOrdenadosExport = $obrasDataExport->pluck('total_calificaciones')->sort()->values();
        $minimoVotosExport = $votosOrdenadosExport->count() > 0 
            ? $votosOrdenadosExport[floor($votosOrdenadosExport->count() * 0.25)] 
            : 1;

        // Aplicar Score Bayesiano
        $topObrasGeneral = $obrasDataExport->map(function($item) use ($promedioGlobalExport, $minimoVotosExport) {
            $R = $item['prioridad_promedio'];
            $v = $item['total_calificaciones'];
            $m = $promedioGlobalExport;
            $C = $minimoVotosExport;
            
            $score = ($C * $m + $R * $v) / ($C + $v);
            
            return [
                'obra' => $item['obra'],
                'prioridad_promedio' => round($item['prioridad_promedio'], 1),
                'total_calificaciones' => $item['total_calificaciones'],
                'score_bayesiano' => round($score, 2)
            ];
        })
        ->sortByDesc('score_bayesiano')
        ->take(10)
        ->values();

        // ============ NUEVAS GRÁFICAS BLOQUE C ============
        $confianzaPoliciaGeneral = \DB::select("
            SELECT confia_policia, COUNT(*) as total FROM encuestas
            WHERE confia_policia IS NOT NULL AND confia_policia != ''
            GROUP BY confia_policia
        ");

        // Problemas de Seguridad
        $problemasSeguridadRaw = Encuesta::whereNotNull('problemas_seguridad')->pluck('problemas_seguridad');
        $problemasLabels = [
            'Corrupción de seguridad', 'Robo a casa', 'Asaltos a transeúntes',
            'Robo de vehículos', 'Extorsión telefónica', 'Venta de drogas',
            'Falta de vigilancia', 'Alcohol en la calle', 'Violencia familiar',
            'Violencia contra adultos mayores', 'Violencia contra animales',
            'Violencia contra discapacitados', 'Bullying escolar',
            'Acoso a mujeres', 'Discriminación LGBTIQ+', 'Riñas entre vecinos',
            'Consumo de drogas en calle'
        ];
        $problemasPromedios = [];
        foreach ($problemasSeguridadRaw as $json) {
            $problemas = is_array($json) ? $json : json_decode($json, true);
            if (is_array($problemas)) {
                foreach ($problemas as $index => $valor) {
                    if (!isset($problemasPromedios[$index])) {
                        $problemasPromedios[$index] = ['suma' => 0, 'count' => 0];
                    }
                    $problemasPromedios[$index]['suma'] += intval($valor);
                    $problemasPromedios[$index]['count']++;
                }
            }
        }
        $problemasSeguridad = collect($problemasPromedios)->map(function($item, $index) use ($problemasLabels) {
            return [
                'problema' => $problemasLabels[$index] ?? "Problema " . ($index + 1),
                'promedio' => $item['count'] > 0 ? round($item['suma'] / $item['count'], 2) : 0,
                'total_respuestas' => $item['count']
            ];
        })->sortByDesc('promedio')->values();

        // Percepción de Lugares
        $lugaresRaw = Encuesta::whereNotNull('lugares_seguros')->pluck('lugares_seguros');
        $lugaresLabels = [
            'Parque comunitario', 'Mercado/tianguis', 'Plaza comercial',
            'Cajero automático', 'Transporte público', 'Exterior de escuela',
            'Calles cercanas', 'Municipio de Tecámac'
        ];
        $lugaresPromedios = [];
        foreach ($lugaresRaw as $json) {
            $lugares = is_array($json) ? $json : json_decode($json, true);
            if (is_array($lugares)) {
                foreach ($lugares as $index => $valor) {
                    if (!isset($lugaresPromedios[$index])) {
                        $lugaresPromedios[$index] = ['suma' => 0, 'count' => 0];
                    }
                    $lugaresPromedios[$index]['suma'] += intval($valor);
                    $lugaresPromedios[$index]['count']++;
                }
            }
        }
        $percepcionLugares = collect($lugaresPromedios)->map(function($item, $index) use ($lugaresLabels) {
            return [
                'lugar' => $lugaresLabels[$index] ?? "Lugar " . ($index + 1),
                'promedio' => $item['count'] > 0 ? round($item['suma'] / $item['count'], 2) : 0,
                'total_respuestas' => $item['count']
            ];
        })->sortByDesc('promedio')->values();

        return [
            'totalEncuestas' => $totalEncuestas,
            'totalPropuestas' => $totalPropuestas,
            'totalReportes' => $totalReportes,
            'encuestasPorColonia' => $encuestasPorColonia,
            'seguridadStats' => $seguridadStats,
            'distrito20Demograficos' => collect($distrito20Demograficos),
            'distrito5Demograficos' => collect($distrito5Demograficos),
            'nivelEducativoData' => collect($nivelEducativoData),
            'distrito20Obras' => collect($distrito20Obras),
            'distrito5Obras' => collect($distrito5Obras),
            'edadDistribucion' => collect($edadDistribucion),
            'estadoCivilDistribucion' => collect($estadoCivilDistribucion),
            'generoEducacion' => collect($generoEducacion),
            'reportesPorTipo' => collect($reportesPorTipo),
            'coloniasMasReportes' => collect($coloniasMasReportes),
            'confianzaPoliciaGeneral' => collect($confianzaPoliciaGeneral),
            'problemasSeguridad' => $problemasSeguridad,
            'percepcionLugares' => $percepcionLugares,
            'topObrasGeneral' => $topObrasGeneral,
        ];
    }

    public function getObrasPorColonia($coloniaId)
    {
        $allObras = \App\Models\ObraPublica::all()->keyBy('id');
        $obras = Encuesta::where('colonia_id', $coloniaId)
            ->whereNotNull('obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) use ($allObras) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = $allObras->get($obraId);
                        if ($obra) {
                            $obras[] = [
                                'obra' => $obra->nombre,
                                'prioridad' => $prioridad,
                                'obra_id' => $obraId
                            ];
                        }
                    }
                }
                return $obras;
            })
            ->groupBy('obra')
            ->map(function($group) {
                return [
                    'obra' => $group->first()['obra'],
                    'prioridad_promedio' => round($group->avg('prioridad'), 1),
                    'total_respuestas' => $group->count()
                ];
            })
            ->values();

        return response()->json($obras);
    }

    public function destroy($id)
    {
        try {
            $encuesta = Encuesta::findOrFail($id);

            // Eliminar archivos de propuestas asociadas
            foreach ($encuesta->propuestas as $propuesta) {
                if ($propuesta->fotografia) {
                    Storage::disk('public')->delete($propuesta->fotografia);
                }
            }

            // Eliminar archivos de reportes asociados
            foreach ($encuesta->reportes as $reporte) {
                if ($reporte->evidencia) {
                    Storage::disk('public')->delete($reporte->evidencia);
                }
            }

            // Eliminar la encuesta (esto también eliminará propuestas y reportes por cascade)
            $encuesta->delete();

            return redirect()->route('admin.encuestas.index')
                ->with('success', 'Encuesta eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.encuestas.index')
                ->with('error', 'Error al eliminar la encuesta: ' . $e->getMessage());
        }
    }
}
