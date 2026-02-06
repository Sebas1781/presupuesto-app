<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Encuesta;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Configurar el PDF
        $pdf = Pdf::loadHTML($this->generateStatsPdfHtml($data))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'Arial'
            ]);

        $filename = 'estadisticas_presupuesto_' . date('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    private function generateStatsHtml($data)
    {
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estadísticas - Presupuesto Participativo 2026</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #9D2449; padding-bottom: 10px; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-box { border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #f9f9f9; }
        .stat-number { font-size: 24px; font-weight: bold; color: #9D2449; }
        .chart-section { margin: 30px 0; page-break-inside: avoid; }
        .chart-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; color: #4E232E; }
        .chart-container { width: 100%; height: 400px; margin: 20px 0; }
        .distrito-charts { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .distrito-chart { border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        .distrito-20 { border-left: 5px solid #9D2449; }
        .distrito-5 { border-left: 5px solid #B3865D; }
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
        <div class="stat-box"><div class="stat-number">' . $data['totalEncuestas'] . '</div><div>Total de Encuestas</div></div>
        <div class="stat-box"><div class="stat-number">' . $data['totalPropuestas'] . '</div><div>Total de Propuestas</div></div>
        <div class="stat-box"><div class="stat-number">' . $data['totalReportes'] . '</div><div>Total de Reportes</div></div>
        <div class="stat-box"><div class="stat-number">' . $data['encuestasPorColonia']->count() . '</div><div>Colonias Participantes</div></div>
    </div>

    <div class="chart-section">
        <h3 class="chart-title">Participación por Colonia</h3>
        <div class="chart-container">
            <canvas id="coloniaChart"></canvas>
        </div>
    </div>';

        // Análisis demográfico por distrito
        if (!$data['distrito20Demograficos']->isEmpty() || !$data['distrito5Demograficos']->isEmpty()) {
            $html .= '<div class="page-break"></div><div class="chart-section">
                <h3 class="chart-title pantone-504">BLOQUE A: Análisis Demográfico por Distrito</h3>
                <div class="distrito-charts">
                    <div class="distrito-chart distrito-20">
                        <h4 class="pantone-420">Distrito 20</h4>
                        <div class="chart-container"><canvas id="distrito20DemograficoChart"></canvas></div>
                    </div>
                    <div class="distrito-chart distrito-5">
                        <h4 class="pantone-465">Distrito 5</h4>
                        <div class="chart-container"><canvas id="distrito5DemograficoChart"></canvas></div>
                    </div>
                </div>
            </div>';
        }

        // Prioridad de obras por distrito
        if (!$data['distrito20Obras']->isEmpty() || !$data['distrito5Obras']->isEmpty()) {
            $html .= '<div class="page-break"></div><div class="chart-section">
                <h3 class="chart-title pantone-465">BLOQUE B: Prioridad de Obras Públicas por Distrito</h3>
                <div class="distrito-charts">
                    <div class="distrito-chart distrito-20">
                        <h4 class="pantone-420">Distrito 20 - Prioridad de Obras</h4>
                        <div class="chart-container"><canvas id="distrito20ObrasChart"></canvas></div>
                    </div>
                    <div class="distrito-chart distrito-5">
                        <h4 class="pantone-465">Distrito 5 - Prioridad de Obras</h4>
                        <div class="chart-container"><canvas id="distrito5ObrasChart"></canvas></div>
                    </div>
                </div>
            </div>';
        }

        // JavaScript para renderizar las gráficas
        $html .= '<script>
        window.addEventListener("load", function() {
            const paletaColores = {
                pantone420: "#9D2449", pantone504: "#4E232E",
                pantone490: "#56242A", pantone465: "#B3865D"
            };

            // Gráfico de Participación por Colonia
            const coloniaData = ' . json_encode($data['encuestasPorColonia']->map(function($item) {
                return ['colonia' => $item->colonia ? $item->colonia->nombre : 'Sin especificar', 'total' => $item->total];
            })->values()) . ';

            if (document.getElementById("coloniaChart")) {
                new Chart(document.getElementById("coloniaChart"), {
                    type: "bar",
                    data: {
                        labels: coloniaData.map(item => item.colonia),
                        datasets: [{
                            label: "Número de Encuestas",
                            data: coloniaData.map(item => item.total),
                            backgroundColor: paletaColores.pantone420,
                            borderColor: paletaColores.pantone504,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } },
                        plugins: { legend: { display: false } }
                    }
                });
            }';

        // Gráficos demográficos
        if (!$data['distrito20Demograficos']->isEmpty()) {
            $distrito20Data = $data['distrito20Demograficos']->groupBy('colonia');
            $html .= 'const distrito20Data = ' . json_encode($distrito20Data) . ';
            const colonias20 = Object.keys(distrito20Data);
            const generos = ["Masculino", "Femenino", "Otro"];
            const coloresGenero = [paletaColores.pantone420, paletaColores.pantone504, paletaColores.pantone490];

            if (document.getElementById("distrito20DemograficoChart")) {
                const datasets20Demo = generos.map((genero, index) => ({
                    label: genero,
                    data: colonias20.map(colonia => distrito20Data[colonia].filter(item => item.genero === genero).reduce((sum, item) => sum + item.total, 0)),
                    backgroundColor: coloresGenero[index], borderColor: coloresGenero[index], borderWidth: 1
                }));
                new Chart(document.getElementById("distrito20DemograficoChart"), {
                    type: "bar", data: { labels: colonias20, datasets: datasets20Demo },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { position: "top" } } }
                });
            }';
        }

        if (!$data['distrito5Demograficos']->isEmpty()) {
            $distrito5Data = $data['distrito5Demograficos']->groupBy('colonia');
            $html .= 'const distrito5Data = ' . json_encode($distrito5Data) . ';
            const colonias5 = Object.keys(distrito5Data);

            if (document.getElementById("distrito5DemograficoChart")) {
                const datasets5Demo = generos.map((genero, index) => ({
                    label: genero,
                    data: colonias5.map(colonia => distrito5Data[colonia].filter(item => item.genero === genero).reduce((sum, item) => sum + item.total, 0)),
                    backgroundColor: coloresGenero[index], borderColor: coloresGenero[index], borderWidth: 1
                }));
                new Chart(document.getElementById("distrito5DemograficoChart"), {
                    type: "bar", data: { labels: colonias5, datasets: datasets5Demo },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { position: "top" } } }
                });
            }';
        }

        // Gráficos de obras
        if (!$data['distrito20Obras']->isEmpty()) {
            $html .= 'const obras20 = ' . json_encode($data['distrito20Obras']) . ';
            if (document.getElementById("distrito20ObrasChart")) {
                new Chart(document.getElementById("distrito20ObrasChart"), {
                    type: "bar",
                    data: { labels: obras20.map(obra => obra.obra), datasets: [{ label: "Prioridad Promedio", data: obras20.map(obra => obra.prioridad_promedio), backgroundColor: paletaColores.pantone420, borderColor: paletaColores.pantone504, borderWidth: 1 }] },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 5 } }, plugins: { legend: { display: false } } }
                });
            }';
        }

        if (!$data['distrito5Obras']->isEmpty()) {
            $html .= 'const obras5 = ' . json_encode($data['distrito5Obras']) . ';
            if (document.getElementById("distrito5ObrasChart")) {
                new Chart(document.getElementById("distrito5ObrasChart"), {
                    type: "bar",
                    data: { labels: obras5.map(obra => obra.obra), datasets: [{ label: "Prioridad Promedio", data: obras5.map(obra => obra.prioridad_promedio), backgroundColor: paletaColores.pantone465, borderColor: paletaColores.pantone490, borderWidth: 1 }] },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 5 } }, plugins: { legend: { display: false } } }
                });
            }';
        }

        $html .= '});
        </script>
    </body>
</html>';

        return $html;
    }

    private function generateStatsPdfHtml($data)
    {
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estadísticas - Presupuesto Participativo 2026</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 15px; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #9D2449; padding-bottom: 10px; }
        .stats-grid { display: table; width: 100%; margin-bottom: 20px; }
        .stats-row { display: table-row; }
        .stat-box { display: table-cell; border: 1px solid #ddd; padding: 10px; text-align: center; background: #f9f9f9; width: 25%; }
        .stat-number { font-size: 18px; font-weight: bold; color: #9D2449; }
        .section { margin: 20px 0; page-break-inside: avoid; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #4E232E; border-bottom: 2px solid #9D2449; padding-bottom: 5px; }
        .data-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 10px; }
        .data-table th { background-color: #9D2449; color: white; font-weight: bold; }
        .data-table tr:nth-child(even) { background-color: #f9f9f9; }
        .distrito-section { margin: 15px 0; }
        .distrito-title { font-size: 12px; font-weight: bold; margin: 10px 0; }
        .distrito-20 { color: #9D2449; }
        .distrito-5 { color: #B3865D; }
        .page-break { page-break-before: always; }

        /* Estilos para gráficos CSS */
        .chart-container { margin: 15px 0; border: 1px solid #ddd; padding: 15px; background: #fafafa; }
        .chart-title { font-weight: bold; margin-bottom: 10px; text-align: center; color: #4E232E; }
        .bar-chart { margin: 10px 0; }
        .bar-item { margin: 5px 0; }
        .bar-label { display: inline-block; width: 150px; font-size: 9px; vertical-align: middle; }
        .bar-visual { display: inline-block; height: 20px; background: #9D2449; margin-right: 5px; vertical-align: middle; }
        .bar-value { display: inline-block; font-size: 9px; font-weight: bold; vertical-align: middle; }

        .pie-chart { text-align: center; margin: 10px 0; }
        .pie-legend { margin: 10px 0; }
        .legend-item { display: inline-block; margin: 5px; font-size: 9px; }
        .legend-color { display: inline-block; width: 12px; height: 12px; margin-right: 3px; vertical-align: middle; }

        .radar-chart { text-align: center; margin: 15px 0; }
        .radar-item { margin: 3px 0; }
        .radar-label { display: inline-block; width: 120px; font-size: 9px; }
        .radar-bar { display: inline-block; height: 15px; background: #9D2449; margin-right: 5px; }
        .radar-value { display: inline-block; font-size: 9px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="color: #9D2449; margin: 0;">Estadísticas del Presupuesto Participativo 2026</h1>
        <p style="margin: 5px 0;">Generado el ' . date('d/m/Y H:i:s') . '</p>
    </div>

    <div class="stats-grid">
        <div class="stats-row">
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
    </div>

    <div class="section">
        <h3 class="section-title">Participación por Colonia</h3>

        <div class="chart-container">
            <div class="chart-title">Número de Encuestas por Colonia</div>
            <div class="bar-chart">';

        // Encontrar el máximo para escalar las barras
        $maxEncuestas = 0;
        foreach ($data['encuestasPorColonia'] as $stat) {
            $maxEncuestas = max($maxEncuestas, $stat->total);
        }

        foreach ($data['encuestasPorColonia'] as $stat) {
            $porcentaje = $maxEncuestas > 0 ? ($stat->total / $maxEncuestas) * 100 : 0;
            $ancho = max($porcentaje * 2, 20); // Mínimo 20px de ancho

            $html .= '<div class="bar-item">
                <span class="bar-label">' . ($stat->colonia ? $stat->colonia->nombre : 'Sin especificar') . ':</span>
                <span class="bar-visual" style="width: ' . $ancho . 'px;"></span>
                <span class="bar-value">' . $stat->total . '</span>
            </div>';
        }

        $html .= '</div>
        </div>

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
                <td style="text-align: center;">' . $stat->total . '</td>
            </tr>';
        }

        $html .= '</tbody></table></div>';

        // BLOQUE A: Análisis Demográfico
        if (!$data['distrito20Demograficos']->isEmpty() || !$data['distrito5Demograficos']->isEmpty()) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="section">
                <h3 class="section-title">BLOQUE A: Análisis Demográfico por Distrito</h3>';

            // Distrito 20 Demográfico
            if (!$data['distrito20Demograficos']->isEmpty()) {
                $html .= '<div class="distrito-section">
                    <h4 class="distrito-title distrito-20">Distrito 20 - Análisis Demográfico</h4>';

                // Procesar datos demográficos
                $generoData20 = [];
                foreach ($data['distrito20Demograficos'] as $demo) {
                    if (!isset($generoData20[$demo->genero])) {
                        $generoData20[$demo->genero] = 0;
                    }
                    $generoData20[$demo->genero] += $demo->total;
                }

                if (!empty($generoData20)) {
                    $html .= '<div class="chart-container">
                        <div class="chart-title">Distribución por Género - Distrito 20</div>
                        <div class="pie-chart">
                            <div class="pie-legend">';

                    $colores = ['#9D2449', '#4E232E', '#56242A', '#B3865D'];
                    $total20 = array_sum($generoData20);
                    $colorIndex = 0;

                    foreach ($generoData20 as $genero => $cantidad) {
                        $porcentaje = $total20 > 0 ? round(($cantidad / $total20) * 100, 1) : 0;
                        $color = $colores[$colorIndex % count($colores)];

                        $html .= '<div class="legend-item">
                            <span class="legend-color" style="background-color: ' . $color . ';"></span>
                            ' . $genero . ': ' . $cantidad . ' (' . $porcentaje . '%)
                        </div>';
                        $colorIndex++;
                    }

                    $html .= '</div>
                        </div>
                    </div>';
                }

                $html .= '<table class="data-table">
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
                        <td style="text-align: center;">' . $demo->total . '</td>
                    </tr>';
                }

                $html .= '</tbody></table></div>';
            }

            // Distrito 5 Demográfico
            if (!$data['distrito5Demograficos']->isEmpty()) {
                $html .= '<div class="distrito-section">
                    <h4 class="distrito-title distrito-5">Distrito 5 - Análisis Demográfico</h4>';

                // Procesar datos demográficos
                $generoData5 = [];
                foreach ($data['distrito5Demograficos'] as $demo) {
                    if (!isset($generoData5[$demo->genero])) {
                        $generoData5[$demo->genero] = 0;
                    }
                    $generoData5[$demo->genero] += $demo->total;
                }

                if (!empty($generoData5)) {
                    $html .= '<div class="chart-container">
                        <div class="chart-title">Distribución por Género - Distrito 5</div>
                        <div class="pie-chart">
                            <div class="pie-legend">';

                    $colores5 = ['#B3865D', '#56242A', '#4E232E', '#9D2449'];
                    $total5 = array_sum($generoData5);
                    $colorIndex = 0;

                    foreach ($generoData5 as $genero => $cantidad) {
                        $porcentaje = $total5 > 0 ? round(($cantidad / $total5) * 100, 1) : 0;
                        $color = $colores5[$colorIndex % count($colores5)];

                        $html .= '<div class="legend-item">
                            <span class="legend-color" style="background-color: ' . $color . ';"></span>
                            ' . $genero . ': ' . $cantidad . ' (' . $porcentaje . '%)
                        </div>';
                        $colorIndex++;
                    }

                    $html .= '</div>
                        </div>
                    </div>';
                }

                $html .= '<table class="data-table">
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
                        <td style="text-align: center;">' . $demo->total . '</td>
                    </tr>';
                }

                $html .= '</tbody></table></div>';
            }

            $html .= '</div>';
        }

        // BLOQUE B: Análisis de Obras
        if (!$data['distrito20Obras']->isEmpty() || !$data['distrito5Obras']->isEmpty()) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="section">
                <h3 class="section-title">BLOQUE B: Prioridad de Obras Públicas por Distrito</h3>';

            // Distrito 20 Obras
            if (!$data['distrito20Obras']->isEmpty()) {
                $html .= '<div class="distrito-section">
                    <h4 class="distrito-title distrito-20">Distrito 20 - Prioridad de Obras por Colonia</h4>';

                // Agrupar obras por colonia
                $obrasPorColonia20 = [];
                foreach ($data['distrito20Obras'] as $obra) {
                    $coloniaNombre = $obra->colonia_nombre ?? 'Sin especificar';

                    if (!isset($obrasPorColonia20[$coloniaNombre])) {
                        $obrasPorColonia20[$coloniaNombre] = [];
                    }
                    $obrasPorColonia20[$coloniaNombre][] = $obra;
                }

                foreach ($obrasPorColonia20 as $coloniaNombre => $obras) {
                    // 1. NOMBRE DE LA COLONIA
                    $html .= '<h5 style="color: #9D2449; font-size: 13px; margin: 20px 0 10px 0; border-bottom: 1px solid #9D2449; padding-bottom: 5px;">'
                        . $coloniaNombre . '</h5>';

                    // 2. GRÁFICA DE PRIORIDAD DE OBRAS
                    $html .= '<div class="chart-container">
                        <div class="chart-title">Prioridad de Obras</div>
                        <div class="bar-chart">';

                    $maxPrioridad = 5;
                    foreach ($obras as $obra) {
                        $prioridad = floatval($obra->prioridad_promedio);
                        $porcentaje = ($prioridad / $maxPrioridad) * 100;
                        $ancho = max($porcentaje * 2, 20);
                        $obraNombre = strlen($obra->obra) > 35 ? substr($obra->obra, 0, 35) . '...' : $obra->obra;

                        $html .= '<div class="bar-item">
                            <span class="bar-label">' . $obraNombre . ':</span>
                            <span class="bar-visual" style="width: ' . $ancho . 'px; background: #9D2449;"></span>
                            <span class="bar-value">' . number_format($prioridad, 1) . '</span>
                        </div>';
                    }

                    $html .= '</div>
                    </div>';

                    // 3. TABLA DE PRIORIDAD CON TOTAL DE CALIFICACIONES
                    $html .= '<table class="data-table" style="margin-top: 10px;">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Obra Pública</th>
                                <th style="width: 20%;">Prioridad Promedio</th>
                                <th style="width: 20%;">Total Calificaciones</th>
                            </tr>
                        </thead>
                        <tbody>';

                    foreach ($obras as $obra) {
                        $html .= '<tr>
                            <td>' . $obra->obra . '</td>
                            <td style="text-align: center;">' . number_format($obra->prioridad_promedio, 1) . '</td>
                            <td style="text-align: center;">' . $obra->total_calificaciones . '</td>
                        </tr>';
                    }

                    $html .= '</tbody>
                    </table>';
                }

                $html .= '</div>';
            }

            // Distrito 5 Obras
            if (!$data['distrito5Obras']->isEmpty()) {
                $html .= '<div class="distrito-section">
                    <h4 class="distrito-title distrito-5">Distrito 5 - Prioridad de Obras por Colonia</h4>';

                // Agrupar obras por colonia
                $obrasPorColonia5 = [];
                foreach ($data['distrito5Obras'] as $obra) {
                    $coloniaNombre = $obra->colonia_nombre ?? 'Sin especificar';

                    if (!isset($obrasPorColonia5[$coloniaNombre])) {
                        $obrasPorColonia5[$coloniaNombre] = [];
                    }
                    $obrasPorColonia5[$coloniaNombre][] = $obra;
                }

                foreach ($obrasPorColonia5 as $coloniaNombre => $obras) {
                    // 1. NOMBRE DE LA COLONIA
                    $html .= '<h5 style="color: #B3865D; font-size: 13px; margin: 20px 0 10px 0; border-bottom: 1px solid #B3865D; padding-bottom: 5px;">'
                        . $coloniaNombre . '</h5>';

                    // 2. GRÁFICA DE PRIORIDAD DE OBRAS
                    $html .= '<div class="chart-container">
                        <div class="chart-title">Prioridad de Obras</div>
                        <div class="bar-chart">';

                    $maxPrioridad = 5;
                    foreach ($obras as $obra) {
                        $prioridad = floatval($obra->prioridad_promedio);
                        $porcentaje = ($prioridad / $maxPrioridad) * 100;
                        $ancho = max($porcentaje * 2, 20);
                        $obraNombre = strlen($obra->obra) > 35 ? substr($obra->obra, 0, 35) . '...' : $obra->obra;

                        $html .= '<div class="bar-item">
                            <span class="bar-label">' . $obraNombre . ':</span>
                            <span class="bar-visual" style="width: ' . $ancho . 'px; background: #B3865D;"></span>
                            <span class="bar-value">' . number_format($prioridad, 1) . '</span>
                        </div>';
                    }

                    $html .= '</div>
                    </div>';

                    // 3. TABLA DE PRIORIDAD CON TOTAL DE CALIFICACIONES
                    $html .= '<table class="data-table" style="margin-top: 10px;">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Obra Pública</th>
                                <th style="width: 20%;">Prioridad Promedio</th>
                                <th style="width: 20%;">Total Calificaciones</th>
                            </tr>
                        </thead>
                        <tbody>';

                    foreach ($obras as $obra) {
                        $html .= '<tr>
                            <td>' . $obra->obra . '</td>
                            <td style="text-align: center;">' . number_format($obra->prioridad_promedio, 1) . '</td>
                            <td style="text-align: center;">' . $obra->total_calificaciones . '</td>
                        </tr>';
                    }

                    $html .= '</tbody>
                    </table>';
                }

                $html .= '</div>';
            }

            $html .= '</div>';
        }

        // Estadísticas de Seguridad
        if (!empty($data['seguridadStats'])) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="section">
                <h3 class="section-title">Estadísticas de Seguridad Pública</h3>';

            $html .= '<div class="chart-container">
                <div class="chart-title">Evaluación de Seguridad Pública (Escala 1-5)</div>
                <div class="radar-chart">';

            foreach ($data['seguridadStats'] as $aspecto => $valor) {
                $valorFloat = floatval($valor);
                $porcentaje = ($valorFloat / 5) * 100;
                $ancho = max($porcentaje * 1.5, 15);
                $aspectoNombre = ucfirst(str_replace('_', ' ', $aspecto));

                $html .= '<div class="radar-item">
                    <span class="radar-label">' . $aspectoNombre . ':</span>
                    <span class="radar-bar" style="width: ' . $ancho . 'px;"></span>
                    <span class="radar-value">' . number_format($valorFloat, 2) . '</span>
                </div>';
            }

            $html .= '</div>
            </div>

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
                    <td style="text-align: center;">' . number_format($valor, 2) . '</td>
                </tr>';
            }

            $html .= '</tbody></table></div>';
        }

        $html .= '
    <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #666;">
        <p>Este reporte fue generado automáticamente por el Sistema de Presupuesto Participativo 2026</p>
        <p>Para visualizar las gráficas interactivas, visite el dashboard web en: <strong>admin/estadisticas</strong></p>
    </div>
</body>
</html>';

        return $html;
    }
}
