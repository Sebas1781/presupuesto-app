<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Encuesta;
use App\Models\Propuesta;
use App\Models\Reporte;
use Illuminate\Http\Request;

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

        // BLOQUE B: Análisis de prioridad de obras por distrito
        $distrito20Obras = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->join('obras_publicas', 'obras_publicas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', 20)
            ->whereNotNull('encuestas.obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = \App\Models\ObraPublica::find($obraId);
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

        $distrito5Obras = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->join('obras_publicas', 'obras_publicas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', 5)
            ->whereNotNull('encuestas.obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = \App\Models\ObraPublica::find($obraId);
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

        // BLOQUE B: Análisis de prioridad de obras por distrito
        $distrito20Obras = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->join('obras_publicas', 'obras_publicas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', '20')
            ->whereNotNull('encuestas.obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = \App\Models\ObraPublica::find($obraId);
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

        $distrito5Obras = Encuesta::join('colonias', 'encuestas.colonia_id', '=', 'colonias.id')
            ->join('obras_publicas', 'obras_publicas.colonia_id', '=', 'colonias.id')
            ->where('colonias.distrito', '5')
            ->whereNotNull('encuestas.obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = \App\Models\ObraPublica::find($obraId);
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
            'coloniasDistrito5'
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

        // Obtener nombres de obras para las calificaciones
        $obrasCalificadasConNombres = [];
        if ($encuesta->obras_calificadas) {
            foreach ($encuesta->obras_calificadas as $obraId => $calificacion) {
                $obra = \App\Models\ObraPublica::find($obraId);
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
                CASE
                    WHEN e.edad BETWEEN 18 AND 25 THEN '18-25'
                    WHEN e.edad BETWEEN 26 AND 35 THEN '26-35'
                    WHEN e.edad BETWEEN 36 AND 45 THEN '36-45'
                    WHEN e.edad BETWEEN 46 AND 55 THEN '46-55'
                    WHEN e.edad > 55 THEN '56+'
                    ELSE 'No especificado'
                END as rango_edad,
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
                CASE
                    WHEN e.edad BETWEEN 18 AND 25 THEN '18-25'
                    WHEN e.edad BETWEEN 26 AND 35 THEN '26-35'
                    WHEN e.edad BETWEEN 36 AND 45 THEN '36-45'
                    WHEN e.edad BETWEEN 46 AND 55 THEN '46-55'
                    WHEN e.edad > 55 THEN '56+'
                    ELSE 'No especificado'
                END as rango_edad,
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
                AVG(CAST(JSON_EXTRACT(e.obras_calificadas, '$.' || op.id) AS UNSIGNED)) as prioridad_promedio,
                COUNT(*) as total_calificaciones
            FROM encuestas e
            JOIN colonias c ON e.colonia_id = c.id
            JOIN obras_publicas op ON op.colonia_id = c.id
            WHERE c.distrito = 20
            AND JSON_EXTRACT(e.obras_calificadas, '$.' || op.id) IS NOT NULL
            GROUP BY c.nombre, op.id, op.nombre
            ORDER BY c.nombre, prioridad_promedio DESC
        ");

        // Distrito 5 - Obras
        $distrito5Obras = \DB::select("
            SELECT
                c.nombre as colonia_nombre,
                op.nombre as obra,
                AVG(CAST(JSON_EXTRACT(e.obras_calificadas, '$.' || op.id) AS UNSIGNED)) as prioridad_promedio,
                COUNT(*) as total_calificaciones
            FROM encuestas e
            JOIN colonias c ON e.colonia_id = c.id
            JOIN obras_publicas op ON op.colonia_id = c.id
            WHERE c.distrito = 5
            AND JSON_EXTRACT(e.obras_calificadas, '$.' || op.id) IS NOT NULL
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
        ];
    }

    public function getObrasPorColonia($coloniaId)
    {
        $obras = Encuesta::where('colonia_id', $coloniaId)
            ->whereNotNull('obras_calificadas')
            ->get()
            ->flatMap(function($encuesta) {
                $obras = [];
                if ($encuesta->obras_calificadas && is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $prioridad) {
                        $obra = \App\Models\ObraPublica::find($obraId);
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
}
