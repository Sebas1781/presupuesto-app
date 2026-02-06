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
        };

        return response()->stream($callback, 200, $headers);
    }

    public function estadisticasPdf()
    {
        // Obtener los mismos datos que el dashboard
        $data = app(DashboardController::class)->getDashboardData();

        // Crear contenido HTML para el PDF
        $html = $this->generateStatsHtml($data);

        // Configurar el PDF
        $options = [
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'margin-top' => '0.5in',
            'margin-right' => '0.5in',
            'margin-bottom' => '0.5in',
            'margin-left' => '0.5in',
            'encoding' => 'UTF-8',
            'javascript-delay' => 2000,
            'enable-javascript' => true,
        ];

        // Generar PDF usando wkhtmltopdf (requiere instalación)
        try {
            $filename = 'estadisticas_presupuesto_' . date('Y-m-d_H-i-s') . '.pdf';

            // Por ahora, devolver HTML ya que wkhtmltopdf no está instalado
            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '.html"');

        } catch (\Exception $e) {
            return response('Error generando PDF: ' . $e->getMessage(), 500);
        }
    }

    private function generateStatsHtml($data)
    {
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estadísticas - Presupuesto Participativo 2026</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #9D2449;
            padding-bottom: 10px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #9D2449;
        }
        .chart-section {
            margin: 30px 0;
            page-break-inside: avoid;
        }
        .chart-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #4E232E;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #9D2449;
            color: white;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .pantone-420 { color: #9D2449; }
        .pantone-504 { color: #4E232E; }
        .pantone-490 { color: #56242A; }
        .pantone-465 { color: #B3865D; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="pantone-420">Estadísticas del Presupuesto Participativo 2026</h1>
        <p>Generado el ' . date('d/m/Y H:i:s') . '</p>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number">' . $data['totalEncuestas'] . '</div>
            <div>Total de Encuestas</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">' . $data['totalPropuestas'] . '</div>
            <div>Total de Propuestas</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">' . $data['totalReportes'] . '</div>
            <div>Total de Reportes</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">' . $data['encuestasPorColonia']->count() . '</div>
            <div>Colonias Participantes</div>
        </div>
    </div>

    <div class="chart-section">
        <h3 class="chart-title">Participación por Colonia</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Colonia</th>
                    <th>Número de Encuestas</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($data['encuestasPorColonia'] as $stat) {
            $html .= '<tr>
                <td>' . ($stat->colonia ? $stat->colonia->nombre : 'Sin especificar') . '</td>
                <td>' . $stat->total . '</td>
            </tr>';
        }

        $html .= '</tbody></table></div>';

        // Agregar sección de seguridad si hay datos
        if (!empty($data['seguridadStats'])) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="chart-section">
                <h3 class="chart-title">Estadísticas de Seguridad Pública</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Aspecto</th>
                            <th>Calificación Promedio</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data['seguridadStats'] as $aspecto => $valor) {
                $html .= '<tr>
                    <td>' . ucfirst(str_replace('_', ' ', $aspecto)) . '</td>
                    <td>' . number_format($valor, 2) . '</td>
                </tr>';
            }

            $html .= '</tbody></table></div>';
        }

        // Distrito 20 - Demográfico
        if (!$data['distrito20Demograficos']->isEmpty()) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="chart-section">
                <h3 class="chart-title">DISTRITO 20 - Análisis Demográfico</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Colonia</th>
                            <th>Género</th>
                            <th>Rango de Edad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data['distrito20Demograficos'] as $demo) {
                $html .= '<tr>
                    <td>' . $demo->colonia . '</td>
                    <td>' . $demo->genero . '</td>
                    <td>' . $demo->rango_edad . '</td>
                    <td>' . $demo->total . '</td>
                </tr>';
            }

            $html .= '</tbody></table></div>';
        }

        // Distrito 5 - Demográfico
        if (!$data['distrito5Demograficos']->isEmpty()) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="chart-section">
                <h3 class="chart-title">DISTRITO 5 - Análisis Demográfico</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Colonia</th>
                            <th>Género</th>
                            <th>Rango de Edad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data['distrito5Demograficos'] as $demo) {
                $html .= '<tr>
                    <td>' . $demo->colonia . '</td>
                    <td>' . $demo->genero . '</td>
                    <td>' . $demo->rango_edad . '</td>
                    <td>' . $demo->total . '</td>
                </tr>';
            }

            $html .= '</tbody></table></div>';
        }

        // Distrito 20 - Obras
        if (!$data['distrito20Obras']->isEmpty()) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="chart-section">
                <h3 class="chart-title">DISTRITO 20 - Prioridad de Obras</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Obra Pública</th>
                            <th>Prioridad Promedio</th>
                            <th>Total Calificaciones</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data['distrito20Obras'] as $obra) {
                $html .= '<tr>
                    <td>' . $obra->obra . '</td>
                    <td>' . number_format($obra->prioridad_promedio, 2) . '</td>
                    <td>' . $obra->total_calificaciones . '</td>
                </tr>';
            }

            $html .= '</tbody></table></div>';
        }

        // Distrito 5 - Obras
        if (!$data['distrito5Obras']->isEmpty()) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="chart-section">
                <h3 class="chart-title">DISTRITO 5 - Prioridad de Obras</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Obra Pública</th>
                            <th>Prioridad Promedio</th>
                            <th>Total Calificaciones</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data['distrito5Obras'] as $obra) {
                $html .= '<tr>
                    <td>' . $obra->obra . '</td>
                    <td>' . number_format($obra->prioridad_promedio, 2) . '</td>
                    <td>' . $obra->total_calificaciones . '</td>
                </tr>';
            }

            $html .= '</tbody></table></div>';
        }

        $html .= '</body></html>';

        return $html;
    }
}
