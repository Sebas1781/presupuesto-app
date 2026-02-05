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

        return view('admin.dashboard', compact(
            'totalEncuestas',
            'totalPropuestas',
            'totalReportes',
            'encuestasPorColonia',
            'encuestasRecientes',
            'desconfianzaPoliciaPorEdad',
            'calificacionSeguridad',
            'horariosInseguros',
            'promediosSeguridad'
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

        return view('admin.estadisticas', compact(
            'totalEncuestas',
            'totalPropuestas',
            'totalReportes',
            'encuestasPorColonia',
            'desconfianzaPoliciaPorEdad',
            'calificacionSeguridad',
            'horariosInseguros',
            'promediosSeguridad'
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
}
