<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Encuesta;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $tipo = $request->get('tipo', 'simple');

        if ($tipo === 'detallado') {
            return $this->exportarDetallado($encuestas);
        }

        return $this->exportarSimple($encuestas);
    }

    private function exportarSimple($encuestas)
    {
        $filename = 'encuestas_simple_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($encuestas) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Encabezados
            fputcsv($file, [
                'ID',
                'Colonia',
                'Género',
                'Edad',
                'Nivel Educativo',
                'Estado Civil',
                'Calificación Obras',
                'Servicio Seguridad',
                'Confía Policía',
                'Horario Inseguro',
                'Emergencia Transporte (1-10)',
                'Caminar Noche (1-10)',
                'Hijos Solos (1-10)',
                'Transporte Público (1-10)',
                'Desea Reporte',
                'Total Propuestas',
                'Total Reportes',
                'Fecha'
            ]);

            // Datos
            foreach ($encuestas as $encuesta) {
                $obrasCalificadas = '';
                if (is_array($encuesta->obras_calificadas)) {
                    foreach ($encuesta->obras_calificadas as $obraId => $calificacion) {
                        $obrasCalificadas .= "Obra $obraId: $calificacion estrellas; ";
                    }
                }

                fputcsv($file, [
                    $encuesta->id,
                    $encuesta->colonia ? $encuesta->colonia->nombre : 'Sin colonia',
                    $encuesta->genero,
                    $encuesta->edad,
                    $encuesta->nivel_educativo,
                    $encuesta->estado_civil,
                    trim($obrasCalificadas, '; ') ?: 'N/A',
                    $encuesta->servicio_seguridad ?: 'N/A',
                    $encuesta->confia_policia ?: 'N/A',
                    $encuesta->horario_inseguro ?: 'N/A',
                    $encuesta->emergencia_transporte ?: 'N/A',
                    $encuesta->caminar_noche ?: 'N/A',
                    $encuesta->hijos_solos ?: 'N/A',
                    $encuesta->transporte_publico ?: 'N/A',
                    $encuesta->desea_reporte ? 'Sí' : 'No',
                    $encuesta->propuestas ? $encuesta->propuestas->count() : 0,
                    $encuesta->reportes ? $encuesta->reportes->count() : 0,
                    $encuesta->created_at ? $encuesta->created_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportarDetallado($encuestas)
    {
        $filename = 'encuestas_detallado_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($encuestas) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Encabezados para el reporte detallado
            fputcsv($file, [
                'ID Encuesta',
                'Colonia',
                'Género',
                'Edad',
                'Nivel Educativo',
                'Estado Civil',
                'Obras Calificadas',
                'Desea Reporte',
                'Fecha Creación',
                'ID Propuesta',
                'Descripción Propuesta',
                'Fecha Propuesta',
                'ID Reporte',
                'Descripción Reporte',
                'Prioridad',
                'Fecha Reporte'
            ]);

            // Datos detallados
            foreach ($encuestas as $encuesta) {
                $obrasCalificadas = is_array($encuesta->obras_calificadas)
                    ? json_encode($encuesta->obras_calificadas)
                    : ($encuesta->obras_calificadas ?? 'N/A');

                // Si tiene propuestas o reportes, mostrar cada uno en una fila
                $hasPropuestas = $encuesta->propuestas && $encuesta->propuestas->count() > 0;
                $hasReportes = $encuesta->reportes && $encuesta->reportes->count() > 0;

                if ($hasPropuestas || $hasReportes) {
                    // Combinar propuestas y reportes para mostrar juntos
                    $maxCount = max(
                        $hasPropuestas ? $encuesta->propuestas->count() : 0,
                        $hasReportes ? $encuesta->reportes->count() : 0
                    );

                    for ($i = 0; $i < $maxCount; $i++) {
                        $propuesta = $hasPropuestas && isset($encuesta->propuestas[$i])
                            ? $encuesta->propuestas[$i] : null;
                        $reporte = $hasReportes && isset($encuesta->reportes[$i])
                            ? $encuesta->reportes[$i] : null;

                        fputcsv($file, [
                            $encuesta->id,
                            $encuesta->colonia ? $encuesta->colonia->nombre : 'Sin colonia',
                            $encuesta->genero,
                            $encuesta->edad,
                            $encuesta->nivel_educativo,
                            $encuesta->estado_civil,
                            $obrasCalificadas,
                            $encuesta->desea_reporte ? 'Sí' : 'No',
                            $encuesta->created_at ? $encuesta->created_at->format('Y-m-d H:i:s') : 'N/A',
                            $propuesta ? $propuesta->id : '',
                            $propuesta ? $propuesta->descripcion : '',
                            $propuesta && $propuesta->created_at ? $propuesta->created_at->format('Y-m-d H:i:s') : '',
                            $reporte ? $reporte->id : '',
                            $reporte ? $reporte->descripcion : '',
                            $reporte ? $reporte->prioridad : '',
                            $reporte && $reporte->created_at ? $reporte->created_at->format('Y-m-d H:i:s') : ''
                        ]);
                    }
                } else {
                    // Si no tiene propuestas ni reportes, mostrar solo la encuesta
                    fputcsv($file, [
                        $encuesta->id,
                        $encuesta->colonia ? $encuesta->colonia->nombre : 'Sin colonia',
                        $encuesta->genero,
                        $encuesta->edad,
                        $encuesta->nivel_educativo,
                        $encuesta->estado_civil,
                        $obrasCalificadas,
                        $encuesta->desea_reporte ? 'Sí' : 'No',
                        $encuesta->created_at ? $encuesta->created_at->format('Y-m-d H:i:s') : 'N/A',
                        '', '', '', '', '', '', ''
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
