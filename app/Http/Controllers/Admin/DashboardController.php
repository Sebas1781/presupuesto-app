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

        return view('admin.dashboard', compact(
            'totalEncuestas',
            'totalPropuestas',
            'totalReportes',
            'encuestasPorColonia',
            'encuestasRecientes'
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
