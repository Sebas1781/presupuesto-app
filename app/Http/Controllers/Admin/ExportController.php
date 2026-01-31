<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Encuesta;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function encuestas(Request $request)
    {
        $query = Encuesta::with(['colonia', 'propuestas', 'reportes']);
        
        // Aplicar filtros si existen
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

        $encuestas = $query->get();

        // Determinar tipo de exportación
        $tipo = $request->get('tipo', 'simple'); // 'simple' o 'detallado'
        
        if ($tipo === 'detallado') {
            return Excel::download(
                new \App\Exports\EncuestasDetalladoExport($encuestas), 
                'encuestas_detallado_' . date('Y-m-d_H-i-s') . '.xlsx'
            );
        }

        return Excel::download(
            new \App\Exports\EncuestasExport($encuestas), 
            'encuestas_simple_' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}
