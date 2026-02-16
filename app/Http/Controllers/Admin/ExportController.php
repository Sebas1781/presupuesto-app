<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Encuesta;
use App\Models\Reporte;
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
    <title>Estadísticas - Presupuesto 2026</title>
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
        <h1 class="pantone-420">Estadísticas del Presupuesto 2026</h1>
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
    <title>Estadísticas - Presupuesto 2026</title>
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
        <h1 style="color: #9D2449; margin: 0;">Estadísticas del Presupuesto 2026</h1>
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
                        <div class="chart-title">Distribución por Género - Distrito 20</div>';

                    $colores = ['#9D2449', '#4E232E', '#56242A', '#B3865D'];
                    $total20 = array_sum($generoData20);
                    $colorIndex = 0;

                    // Crear gráfico de barras horizontales tipo pie
                    $html .= '<div class="bar-chart" style="margin: 15px 0;">';

                    foreach ($generoData20 as $genero => $cantidad) {
                        $porcentaje = $total20 > 0 ? round(($cantidad / $total20) * 100, 1) : 0;
                        $color = $colores[$colorIndex % count($colores)];
                        $ancho = max($porcentaje * 3, 30); // Hacer barras más grandes

                        // Mostrar nombre más amigable para LGBTIITTQ
                        $generoDisplay = $genero === 'LGBTIITTQ' ? 'LGBTIQ+' : $genero;

                        $html .= '<div class="bar-item" style="margin: 8px 0;">
                            <span class="bar-label" style="width: 80px; display: inline-block;">' . $generoDisplay . ':</span>
                            <span class="bar-visual" style="width: ' . $ancho . 'px; height: 25px; background: ' . $color . '; display: inline-block; margin-right: 10px;"></span>
                            <span class="bar-value" style="font-weight: bold;">' . $porcentaje . '%</span>
                        </div>';
                        $colorIndex++;
                    }

                    $html .= '</div>
                    </div>';
                }

                // Tabla simplificada solo con porcentajes por género
                $html .= '<table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Colonia</th>
                                <th style="width: 15%;">Masculino</th>
                                <th style="width: 15%;">Femenino</th>
                                <th style="width: 15%;">LGBTIQ+</th>
                                <th style="width: 15%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>';

                // Agrupar datos por colonia
                $coloniaData20 = [];
                foreach ($data['distrito20Demograficos'] as $demo) {
                    $colonia = $demo->colonia ?? 'Sin especificar';
                    if (!isset($coloniaData20[$colonia])) {
                        $coloniaData20[$colonia] = ['Masculino' => 0, 'Femenino' => 0, 'LGBTIITTQ' => 0];
                    }
                    $coloniaData20[$colonia][$demo->genero] = ($coloniaData20[$colonia][$demo->genero] ?? 0) + $demo->total;
                }

                foreach ($coloniaData20 as $colonia => $generos) {
                    $totalColonia = array_sum($generos);
                    $masculinoPct = $totalColonia > 0 ? round(($generos['Masculino'] / $totalColonia) * 100, 1) : 0;
                    $femeninoPct = $totalColonia > 0 ? round(($generos['Femenino'] / $totalColonia) * 100, 1) : 0;
                    $lgbtqPct = $totalColonia > 0 ? round(($generos['LGBTIITTQ'] / $totalColonia) * 100, 1) : 0;

                    $html .= '<tr>
                        <td>' . $colonia . '</td>
                        <td style="text-align: center;">' . $generos['Masculino'] . ' (' . $masculinoPct . '%)</td>
                        <td style="text-align: center;">' . $generos['Femenino'] . ' (' . $femeninoPct . '%)</td>
                        <td style="text-align: center;">' . $generos['LGBTIITTQ'] . ' (' . $lgbtqPct . '%)</td>
                        <td style="text-align: center;">' . $totalColonia . '</td>
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
                        <div class="chart-title">Distribución por Género - Distrito 5</div>';

                    $colores5 = ['#B3865D', '#56242A', '#4E232E', '#9D2449'];
                    $total5 = array_sum($generoData5);
                    $colorIndex = 0;

                    // Crear gráfico de barras horizontales tipo pie
                    $html .= '<div class="bar-chart" style="margin: 15px 0;">';

                    foreach ($generoData5 as $genero => $cantidad) {
                        $porcentaje = $total5 > 0 ? round(($cantidad / $total5) * 100, 1) : 0;
                        $color = $colores5[$colorIndex % count($colores5)];
                        $ancho = max($porcentaje * 3, 30); // Hacer barras más grandes

                        // Mostrar nombre más amigable para LGBTIITTQ
                        $generoDisplay = $genero === 'LGBTIITTQ' ? 'LGBTIQ+' : $genero;

                        $html .= '<div class="bar-item" style="margin: 8px 0;">
                            <span class="bar-label" style="width: 80px; display: inline-block;">' . $generoDisplay . ':</span>
                            <span class="bar-visual" style="width: ' . $ancho . 'px; height: 25px; background: ' . $color . '; display: inline-block; margin-right: 10px;"></span>
                            <span class="bar-value" style="font-weight: bold;">' . $porcentaje . '%</span>
                        </div>';
                        $colorIndex++;
                    }

                    $html .= '</div>
                    </div>';
                }

                // Tabla simplificada solo con porcentajes por género
                $html .= '<table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Colonia</th>
                                <th style="width: 15%;">Masculino</th>
                                <th style="width: 15%;">Femenino</th>
                                <th style="width: 15%;">LGBTIQ+</th>
                                <th style="width: 15%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>';

                // Agrupar datos por colonia para Distrito 5
                $coloniaData5 = [];
                foreach ($data['distrito5Demograficos'] as $demo) {
                    $colonia = $demo->colonia ?? 'Sin especificar';
                    if (!isset($coloniaData5[$colonia])) {
                        $coloniaData5[$colonia] = ['Masculino' => 0, 'Femenino' => 0, 'LGBTIITTQ' => 0];
                    }
                    $coloniaData5[$colonia][$demo->genero] = ($coloniaData5[$colonia][$demo->genero] ?? 0) + $demo->total;
                }

                foreach ($coloniaData5 as $colonia => $generos) {
                    $totalColonia = array_sum($generos);
                    $masculinoPct = $totalColonia > 0 ? round(($generos['Masculino'] / $totalColonia) * 100, 1) : 0;
                    $femeninoPct = $totalColonia > 0 ? round(($generos['Femenino'] / $totalColonia) * 100, 1) : 0;
                    $lgbtqPct = $totalColonia > 0 ? round(($generos['LGBTIITTQ'] / $totalColonia) * 100, 1) : 0;

                    $html .= '<tr>
                        <td>' . $colonia . '</td>
                        <td style="text-align: center;">' . $generos['Masculino'] . ' (' . $masculinoPct . '%)</td>
                        <td style="text-align: center;">' . $generos['Femenino'] . ' (' . $femeninoPct . '%)</td>
                        <td style="text-align: center;">' . $generos['LGBTIITTQ'] . ' (' . $lgbtqPct . '%)</td>
                        <td style="text-align: center;">' . $totalColonia . '</td>
                    </tr>';
                }

                $html .= '</tbody></table></div>';
            }

            $html .= '</div>';
        }

        // BLOQUE A: Análisis de Nivel Educativo General
        if (!$data['nivelEducativoData']->isEmpty()) {
            $html .= '<div class="section">
                <h4 class="section-title" style="color: #4E232E; margin-top: 30px;">NIVEL EDUCATIVO DE LA POBLACIÓN GENERAL</h4>';

            $html .= '<div class="chart-container" style="margin: 15px 0;">
                <div class="chart-title" style="font-weight: bold; margin-bottom: 10px; text-align: center;">Distribución de Nivel Educativo</div>';

            $totalEducacion = $data['nivelEducativoData']->sum('total');
            $coloresEducacion = ['#9D2449', '#4E232E', '#56242A', '#B3865D', '#A0745A', '#8B6B47'];
            $colorIndex = 0;

            $html .= '<div class="bar-chart">';

            foreach ($data['nivelEducativoData'] as $educacion) {
                $porcentaje = $totalEducacion > 0 ? round(($educacion->total / $totalEducacion) * 100, 1) : 0;
                $color = $coloresEducacion[$colorIndex % count($coloresEducacion)];
                $ancho = max($porcentaje * 4, 30);

                $html .= '<div class="bar-item" style="margin: 8px 0;">
                    <span class="bar-label" style="width: 180px; display: inline-block; font-size: 10px;">' . $educacion->nivel_educativo . ':</span>
                    <span class="bar-visual" style="width: ' . $ancho . 'px; height: 25px; background: ' . $color . '; display: inline-block; margin-right: 10px;"></span>
                    <span class="bar-value" style="font-size: 10px; font-weight: bold;">' . $educacion->total . ' (' . $porcentaje . '%)</span>
                </div>';
                $colorIndex++;
            }

            $html .= '</div>
            </div>';

            $html .= '<table class="data-table" style="margin-top: 15px;">
                    <thead>
                        <tr>
                            <th style="width: 60%;">Nivel Educativo</th>
                            <th style="width: 20%;">Total</th>
                            <th style="width: 20%;">Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data['nivelEducativoData'] as $educacion) {
                $porcentaje = $totalEducacion > 0 ? round(($educacion->total / $totalEducacion) * 100, 1) : 0;

                $html .= '<tr>
                    <td>' . $educacion->nivel_educativo . '</td>
                    <td style="text-align: center;">' . $educacion->total . '</td>
                    <td style="text-align: center;">' . $porcentaje . '%</td>
                </tr>';
            }

            $html .= '</tbody></table></div>';
        }

        // ============ NUEVAS GRÁFICAS BLOQUE A ============

        // Distribución por Rango de Edad
        if (!$data['edadDistribucion']->isEmpty()) {
            $html .= '<div class="section">
                <h4 class="section-title" style="color: #4E232E;">DISTRIBUCIÓN POR RANGO DE EDAD</h4>';

            $html .= '<div class="chart-container">
                <div class="chart-title">Distribución por Edad de los Encuestados</div>
                <div class="bar-chart">';

            $totalEdad = $data['edadDistribucion']->sum('total');
            $coloresEdad = ['#9D2449', '#4E232E', '#56242A', '#B3865D', '#A0745A'];
            $ci = 0;

            foreach ($data['edadDistribucion'] as $edad) {
                $pct = $totalEdad > 0 ? round(($edad->total / $totalEdad) * 100, 1) : 0;
                $color = $coloresEdad[$ci % count($coloresEdad)];
                $ancho = max($pct * 3, 25);

                $html .= '<div class="bar-item" style="margin: 8px 0;">
                    <span class="bar-label" style="width: 150px; display: inline-block;">' . $edad->edad . ':</span>
                    <span class="bar-visual" style="width: ' . $ancho . 'px; height: 22px; background: ' . $color . '; display: inline-block; margin-right: 8px;"></span>
                    <span class="bar-value">' . $edad->total . ' (' . $pct . '%)</span>
                </div>';
                $ci++;
            }

            $html .= '</div></div>';

            $html .= '<table class="data-table"><thead><tr>
                <th>Rango de Edad</th><th>Total</th><th>Porcentaje</th>
                </tr></thead><tbody>';
            foreach ($data['edadDistribucion'] as $edad) {
                $pct = $totalEdad > 0 ? round(($edad->total / $totalEdad) * 100, 1) : 0;
                $html .= '<tr><td>' . $edad->edad . '</td><td style="text-align:center;">' . $edad->total . '</td><td style="text-align:center;">' . $pct . '%</td></tr>';
            }
            $html .= '</tbody></table></div>';
        }

        // Estado Civil
        if (!$data['estadoCivilDistribucion']->isEmpty()) {
            $html .= '<div class="section">
                <h4 class="section-title" style="color: #56242A;">ESTADO CIVIL DE LA POBLACIÓN</h4>';

            $html .= '<div class="chart-container">
                <div class="chart-title">Estado Civil de los Encuestados</div>
                <div class="bar-chart">';

            $totalEC = $data['estadoCivilDistribucion']->sum('total');
            $coloresEC = ['#9D2449', '#4E232E', '#56242A', '#B3865D', '#A0745A'];
            $ci = 0;

            foreach ($data['estadoCivilDistribucion'] as $ec) {
                $pct = $totalEC > 0 ? round(($ec->total / $totalEC) * 100, 1) : 0;
                $color = $coloresEC[$ci % count($coloresEC)];
                $ancho = max($pct * 3, 25);

                $html .= '<div class="bar-item" style="margin: 8px 0;">
                    <span class="bar-label" style="width: 120px; display: inline-block;">' . $ec->estado_civil . ':</span>
                    <span class="bar-visual" style="width: ' . $ancho . 'px; height: 22px; background: ' . $color . '; display: inline-block; margin-right: 8px;"></span>
                    <span class="bar-value">' . $ec->total . ' (' . $pct . '%)</span>
                </div>';
                $ci++;
            }

            $html .= '</div></div>';

            $html .= '<table class="data-table"><thead><tr>
                <th>Estado Civil</th><th>Total</th><th>Porcentaje</th>
                </tr></thead><tbody>';
            foreach ($data['estadoCivilDistribucion'] as $ec) {
                $pct = $totalEC > 0 ? round(($ec->total / $totalEC) * 100, 1) : 0;
                $html .= '<tr><td>' . $ec->estado_civil . '</td><td style="text-align:center;">' . $ec->total . '</td><td style="text-align:center;">' . $pct . '%</td></tr>';
            }
            $html .= '</tbody></table></div>';
        }

        // Género × Nivel Educativo
        if (!$data['generoEducacion']->isEmpty()) {
            $html .= '<div class="section">
                <h4 class="section-title" style="color: #B3865D;">GÉNERO POR NIVEL EDUCATIVO</h4>';

            // Build cross-tab data
            $geData = [];
            $niveles = [];
            $generosGE = [];
            foreach ($data['generoEducacion'] as $item) {
                $gen = $item->genero;
                $niv = $item->nivel_educativo;
                if (!in_array($niv, $niveles)) $niveles[] = $niv;
                if (!in_array($gen, $generosGE)) $generosGE[] = $gen;
                $geData[$niv][$gen] = $item->total;
            }

            $genLabels = ['LGBTIITTQ' => 'LGBTIQ+'];

            $html .= '<table class="data-table"><thead><tr><th>Nivel Educativo</th>';
            foreach ($generosGE as $gen) {
                $html .= '<th style="text-align:center;">' . ($genLabels[$gen] ?? $gen) . '</th>';
            }
            $html .= '<th style="text-align:center;">Total</th></tr></thead><tbody>';

            foreach ($niveles as $niv) {
                $html .= '<tr><td>' . $niv . '</td>';
                $totalNiv = 0;
                foreach ($generosGE as $gen) {
                    $val = $geData[$niv][$gen] ?? 0;
                    $totalNiv += $val;
                    $html .= '<td style="text-align:center;">' . $val . '</td>';
                }
                $html .= '<td style="text-align:center; font-weight:bold;">' . $totalNiv . '</td></tr>';
            }

            $html .= '</tbody></table></div>';
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

        // ============ NUEVAS GRÁFICAS BLOQUE B ============

        // Top Obras con Mayor Prioridad (general)
        if (isset($data['topObrasGeneral']) && !$data['topObrasGeneral']->isEmpty()) {
            $html .= '<div class="section">
                <h4 class="section-title" style="color: #9D2449;">TOP 10 OBRAS CON MAYOR PRIORIDAD</h4>
                <p style="font-size: 10px; color: #666; margin-top: -8px; margin-bottom: 12px;">Ranking ponderado considerando promedio de calificación y número de respuestas</p>';

            $html .= '<div class="chart-container">
                <div class="chart-title">Obras Públicas Mejor Calificadas (General)</div>
                <div class="bar-chart">';

            foreach ($data['topObrasGeneral'] as $obra) {
                $prioridad = floatval($obra['prioridad_promedio']);
                $ancho = max(($prioridad / 5) * 200, 20);
                $obraNombre = strlen($obra['obra']) > 40 ? substr($obra['obra'], 0, 40) . '...' : $obra['obra'];
                $score = isset($obra['score_bayesiano']) ? $obra['score_bayesiano'] : $prioridad;

                $html .= '<div class="bar-item" style="margin: 6px 0;">
                    <span class="bar-label" style="width: 200px; display: inline-block; font-size: 9px;">' . $obraNombre . ':</span>
                    <span class="bar-visual" style="width: ' . $ancho . 'px; height: 20px; background: #9D2449; display: inline-block; margin-right: 8px;"></span>
                    <span class="bar-value" style="font-size: 9px;">Promedio: ' . number_format($prioridad, 1) . ' | Calificaciones: ' . $obra['total_calificaciones'] . ' | Score: ' . number_format($score, 2) . '</span>
                </div>';
            }

            $html .= '</div></div>';

            $html .= '<table class="data-table"><thead><tr>
                <th>Obra Pública</th><th>Prioridad Promedio</th><th>Total Calificaciones</th><th>Score Ponderado</th>
                </tr></thead><tbody>';
            foreach ($data['topObrasGeneral'] as $obra) {
                $score = isset($obra['score_bayesiano']) ? number_format($obra['score_bayesiano'], 2) : '-';
                $html .= '<tr><td>' . $obra['obra'] . '</td><td style="text-align:center;">' . number_format($obra['prioridad_promedio'], 1) . '</td><td style="text-align:center;">' . $obra['total_calificaciones'] . '</td><td style="text-align:center; font-weight: bold; color: #9D2449;">' . $score . '</td></tr>';
            }
            $html .= '</tbody></table></div>';
        }

        // Reportes por Tipo
        if (!$data['reportesPorTipo']->isEmpty()) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="section">
                <h3 class="section-title" style="color: #4E232E;">ANÁLISIS DE REPORTES CIUDADANOS</h3>';

            $html .= '<div class="chart-container">
                <div class="chart-title">Distribución de Reportes por Tipo</div>
                <div class="bar-chart">';

            $totalRep = $data['reportesPorTipo']->sum('total');
            $coloresRep = ['#9D2449', '#4E232E', '#56242A', '#B3865D'];
            $ci = 0;

            foreach ($data['reportesPorTipo'] as $rep) {
                $pct = $totalRep > 0 ? round(($rep->total / $totalRep) * 100, 1) : 0;
                $color = $coloresRep[$ci % count($coloresRep)];
                $ancho = max($pct * 3, 25);
                $html .= '<div class="bar-item" style="margin: 8px 0;">
                    <span class="bar-label" style="width: 200px; display: inline-block;">' . $rep->tipo_reporte . ':</span>
                    <span class="bar-visual" style="width: ' . $ancho . 'px; height: 22px; background: ' . $color . '; display: inline-block; margin-right: 8px;"></span>
                    <span class="bar-value">' . $rep->total . ' (' . $pct . '%)</span>
                </div>';
                $ci++;
            }

            $html .= '</div></div>';

            $html .= '<table class="data-table"><thead><tr>
                <th>Tipo de Reporte</th><th>Total</th><th>Porcentaje</th>
                </tr></thead><tbody>';
            foreach ($data['reportesPorTipo'] as $rep) {
                $pct = $totalRep > 0 ? round(($rep->total / $totalRep) * 100, 1) : 0;
                $html .= '<tr><td>' . $rep->tipo_reporte . '</td><td style="text-align:center;">' . $rep->total . '</td><td style="text-align:center;">' . $pct . '%</td></tr>';
            }
            $html .= '</tbody></table>';
        }

        // Colonias con Mayor Solicitud de Reportes
        if (!$data['coloniasMasReportes']->isEmpty()) {
            $html .= '<div class="chart-container" style="margin-top: 20px;">
                <div class="chart-title">Colonias con Mayor Solicitud de Reportes</div>
                <div class="bar-chart">';

            $maxColRep = $data['coloniasMasReportes']->max('total');
            foreach ($data['coloniasMasReportes'] as $col) {
                $ancho = $maxColRep > 0 ? max(($col->total / $maxColRep) * 200, 20) : 20;
                $colNombre = $col->colonia ?? 'Sin especificar';
                $html .= '<div class="bar-item" style="margin: 6px 0;">
                    <span class="bar-label" style="width: 150px; display: inline-block;">' . $colNombre . ':</span>
                    <span class="bar-visual" style="width: ' . $ancho . 'px; height: 20px; background: #56242A; display: inline-block; margin-right: 8px;"></span>
                    <span class="bar-value">' . $col->total . '</span>
                </div>';
            }

            $html .= '</div></div>';

            if (!$data['reportesPorTipo']->isEmpty()) {
                $html .= '</div>';
            }
        }

        // BLOQUE C: Estadísticas de Seguridad
        if (!empty($data['seguridadStats'])) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="section">
                <h3 class="section-title">BLOQUE C: Estadísticas de Seguridad Pública</h3>';

            $html .= '<div class="chart-container">
                <div class="chart-title">Evaluación de Seguridad Pública (Escala 1-10)</div>
                <div class="radar-chart">';

            $nombresSeguridad = [
                'emergencia_transporte' => 'Emergencia y Transporte',
                'caminar_noche' => 'Caminar de Noche',
                'hijos_solos' => 'Hijos Caminando Solos',
                'transporte_publico' => 'Transporte Público'
            ];

            foreach ($data['seguridadStats'] as $aspecto => $valor) {
                $valorFloat = floatval($valor);
                $porcentaje = ($valorFloat / 10) * 100;
                $ancho = max($porcentaje * 2, 15);
                $aspectoNombre = $nombresSeguridad[$aspecto] ?? ucfirst(str_replace('_', ' ', $aspecto));

                $html .= '<div class="radar-item">
                    <span class="radar-label" style="width: 160px;">' . $aspectoNombre . ':</span>
                    <span class="radar-bar" style="width: ' . $ancho . 'px;"></span>
                    <span class="radar-value">' . number_format($valorFloat, 1) . '/10</span>
                </div>';
            }

            $html .= '</div></div>';

            $html .= '<table class="data-table">
                    <thead><tr><th>Aspecto</th><th>Calificación Promedio</th></tr></thead>
                    <tbody>';
            foreach ($data['seguridadStats'] as $aspecto => $valor) {
                $aspectoNombre = $nombresSeguridad[$aspecto] ?? ucfirst(str_replace('_', ' ', $aspecto));
                $html .= '<tr><td>' . $aspectoNombre . '</td><td style="text-align:center;">' . number_format($valor, 1) . '/10</td></tr>';
            }
            $html .= '</tbody></table>';
        }

        // ============ NUEVAS GRÁFICAS BLOQUE C ============

        // Confianza en la Policía
        if (!$data['confianzaPoliciaGeneral']->isEmpty()) {
            if (empty($data['seguridadStats'])) {
                $html .= '<div class="page-break"></div>';
                $html .= '<div class="section">
                    <h3 class="section-title">BLOQUE C: Estadísticas de Seguridad Pública</h3>';
            }

            $html .= '<div class="chart-container" style="margin-top: 20px;">
                <div class="chart-title">¿Confía en la Policía?</div>
                <div class="bar-chart">';

            $totalConf = $data['confianzaPoliciaGeneral']->sum('total');
            $coloresConf = ['Sí' => '#28a745', 'Si' => '#28a745', 'No' => '#dc3545'];

            foreach ($data['confianzaPoliciaGeneral'] as $conf) {
                $pct = $totalConf > 0 ? round(($conf->total / $totalConf) * 100, 1) : 0;
                $color = $coloresConf[$conf->confia_policia] ?? '#ffc107';
                $ancho = max($pct * 2.5, 30);

                $html .= '<div class="bar-item" style="margin: 10px 0;">
                    <span class="bar-label" style="width: 50px; display: inline-block; font-size: 12px; font-weight: bold;">' . $conf->confia_policia . ':</span>
                    <span class="bar-visual" style="width: ' . $ancho . 'px; height: 28px; background: ' . $color . '; display: inline-block; margin-right: 10px;"></span>
                    <span class="bar-value" style="font-size: 11px;">' . $conf->total . ' (' . $pct . '%)</span>
                </div>';
            }

            $html .= '</div></div>';
        }

        // Problemas de Seguridad
        if (!$data['problemasSeguridad']->isEmpty()) {
            $html .= '<div class="page-break"></div>';
            $html .= '<div class="section">
                <h4 class="section-title" style="color: #56242A;">PROBLEMAS DE SEGURIDAD: NIVEL DE PREOCUPACIÓN</h4>
                <p style="font-size: 9px; color: #666;">Escala: 1 (No preocupa) - 4 (Preocupa mucho)</p>';

            $html .= '<div class="chart-container">
                <div class="chart-title">Preocupación Ciudadana por Problemas de Seguridad</div>
                <div class="bar-chart">';

            foreach ($data['problemasSeguridad'] as $prob) {
                $promedio = floatval($prob['promedio']);
                $ancho = max($promedio * 60, 15);
                if ($promedio >= 3) $color = '#dc3545';
                elseif ($promedio >= 2.5) $color = '#fd7e14';
                elseif ($promedio >= 2) $color = '#ffc107';
                else $color = '#28a745';

                $html .= '<div class="bar-item" style="margin: 5px 0;">
                    <span class="bar-label" style="width: 200px; display: inline-block; font-size: 8px;">' . $prob['problema'] . ':</span>
                    <span class="bar-visual" style="width: ' . $ancho . 'px; height: 18px; background: ' . $color . '; display: inline-block; margin-right: 5px;"></span>
                    <span class="bar-value" style="font-size: 8px;">' . number_format($promedio, 2) . '</span>
                </div>';
            }

            $html .= '</div></div>';

            $html .= '<table class="data-table" style="font-size: 9px;"><thead><tr>
                <th>Problema</th><th>Promedio</th><th>Respuestas</th>
                </tr></thead><tbody>';
            foreach ($data['problemasSeguridad'] as $prob) {
                $html .= '<tr><td>' . $prob['problema'] . '</td><td style="text-align:center;">' . number_format($prob['promedio'], 2) . '</td><td style="text-align:center;">' . $prob['total_respuestas'] . '</td></tr>';
            }
            $html .= '</tbody></table></div>';
        }

        // Percepción de Seguridad por Lugar
        if (!$data['percepcionLugares']->isEmpty()) {
            $html .= '<div class="section">
                <h4 class="section-title" style="color: #4E232E;">PERCEPCIÓN DE SEGURIDAD POR LUGAR</h4>
                <p style="font-size: 9px; color: #666;">Escala: 1 (Totalmente inseguro) - 4 (Seguro)</p>';

            $html .= '<div class="chart-container">
                <div class="chart-title">Sensación de Seguridad en Diferentes Lugares</div>
                <div class="bar-chart">';

            foreach ($data['percepcionLugares'] as $lugar) {
                $promedio = floatval($lugar['promedio']);
                $ancho = max($promedio * 60, 15);
                if ($promedio >= 3) $color = '#28a745';
                elseif ($promedio >= 2.5) $color = '#ffc107';
                elseif ($promedio >= 2) $color = '#fd7e14';
                else $color = '#dc3545';

                $html .= '<div class="bar-item" style="margin: 8px 0;">
                    <span class="bar-label" style="width: 180px; display: inline-block; font-size: 9px;">' . $lugar['lugar'] . ':</span>
                    <span class="bar-visual" style="width: ' . $ancho . 'px; height: 22px; background: ' . $color . '; display: inline-block; margin-right: 8px;"></span>
                    <span class="bar-value" style="font-size: 9px;">' . number_format($promedio, 2) . '/4</span>
                </div>';
            }

            $html .= '</div></div>';

            $html .= '<table class="data-table"><thead><tr>
                <th>Lugar</th><th>Promedio Seguridad</th><th>Respuestas</th>
                </tr></thead><tbody>';
            foreach ($data['percepcionLugares'] as $lugar) {
                $html .= '<tr><td>' . $lugar['lugar'] . '</td><td style="text-align:center;">' . number_format($lugar['promedio'], 2) . '/4</td><td style="text-align:center;">' . $lugar['total_respuestas'] . '</td></tr>';
            }
            $html .= '</tbody></table></div>';
        }

        // Close any open section from seguridad
        if (!empty($data['seguridadStats']) || !$data['confianzaPoliciaGeneral']->isEmpty()) {
            $html .= '</div>';
        }

        $html .= '
    <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #666;">
        <p>Este reporte fue generado automáticamente por el Sistema de Presupuesto 2026</p>
        <p>Para visualizar las gráficas interactivas, visite el dashboard web en: <strong>admin/estadisticas</strong></p>
    </div>
</body>
</html>';

        return $html;
    }

    /**
     * Exportar todos los reportes anónimos a PDF
     */
    public function reportesPdf(Request $request)
    {
        $query = Reporte::with(['encuesta.colonia']);

        // Aplicar filtros si existen
        if ($request->has('colonia_id') && $request->colonia_id) {
            $query->whereHas('encuesta', function ($q) use ($request) {
                $q->where('colonia_id', $request->colonia_id);
            });
        }

        if ($request->has('fecha_desde') && $request->fecha_desde) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta') && $request->fecha_hasta) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $reportes = $query->orderBy('created_at', 'desc')->get();

        $html = $this->generateReportesPdfHtml($reportes);

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'Arial'
            ]);

        $filename = 'reportes_anonimos_' . date('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generar HTML para el PDF de reportes anónimos
     */
    private function generateReportesPdfHtml($reportes)
    {
        $totalReportes = $reportes->count();
        $tiposCount = $reportes->groupBy('tipo_reporte')->map->count();

        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reportes Anónimos - Presupuesto 2026</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #9D2449; padding-bottom: 15px; }
        .header h1 { color: #9D2449; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 12px; margin: 2px 0; }
        .summary-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
        .summary-box h3 { color: #4E232E; margin-top: 0; font-size: 14px; }
        .summary-grid { display: table; width: 100%; }
        .summary-item { display: table-cell; text-align: center; padding: 8px; }
        .summary-number { font-size: 20px; font-weight: bold; color: #9D2449; }
        .summary-label { font-size: 10px; color: #666; }
        .reporte-card { border: 1px solid #ddd; border-radius: 5px; margin-bottom: 15px; page-break-inside: avoid; }
        .reporte-header { background: #4E232E; color: white; padding: 8px 12px; border-radius: 5px 5px 0 0; }
        .reporte-header h4 { margin: 0; font-size: 12px; }
        .reporte-body { padding: 12px; }
        .reporte-meta { display: table; width: 100%; margin-bottom: 8px; }
        .reporte-meta-item { display: table-cell; padding-right: 15px; }
        .reporte-meta-label { font-weight: bold; color: #4E232E; font-size: 10px; }
        .reporte-meta-value { font-size: 11px; }
        .reporte-descripcion { background: #f8f9fa; padding: 10px; border-radius: 3px; margin-top: 8px; line-height: 1.5; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; color: white; }
        .badge-danger { background: #dc3545; }
        .badge-warning { background: #ffc107; color: #333; }
        .badge-info { background: #17a2b8; }
        .badge-secondary { background: #6c757d; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .no-reportes { text-align: center; padding: 40px; color: #666; }
        .tipo-summary { margin-top: 8px; }
        .tipo-row { display: table; width: 100%; margin: 3px 0; }
        .tipo-name { display: table-cell; width: 70%; }
        .tipo-count { display: table-cell; width: 30%; text-align: right; font-weight: bold; color: #9D2449; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reportes Anónimos</h1>
        <p>Presupuesto Participativo 2026 - Municipio de Soledad de Graciano Sánchez</p>
        <p>Fecha de generación: ' . date('d/m/Y H:i') . '</p>
    </div>

    <div class="summary-box">
        <h3>Resumen General</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">' . $totalReportes . '</div>
                <div class="summary-label">Total de Reportes</div>
            </div>';

        foreach ($tiposCount as $tipo => $count) {
            $html .= '
            <div class="summary-item">
                <div class="summary-number">' . $count . '</div>
                <div class="summary-label">' . e($tipo) . '</div>
            </div>';
        }

        $html .= '
        </div>
    </div>';

        if ($reportes->isEmpty()) {
            $html .= '
    <div class="no-reportes">
        <p>No se encontraron reportes anónimos.</p>
    </div>';
        } else {
            foreach ($reportes as $index => $reporte) {
                $tipoClass = match($reporte->tipo_reporte) {
                    'Inseguridad' => 'badge-danger',
                    'Alumbrado Público' => 'badge-warning',
                    'Baches' => 'badge-info',
                    default => 'badge-secondary',
                };

                $coloniaNombre = $reporte->encuesta && $reporte->encuesta->colonia
                    ? e($reporte->encuesta->colonia->nombre)
                    : 'N/A';

                $fecha = $reporte->created_at
                    ? $reporte->created_at->format('d/m/Y H:i')
                    : 'N/A';

                $html .= '
    <div class="reporte-card">
        <div class="reporte-header">
            <h4>Reporte #' . ($index + 1) . ' <span class="badge ' . $tipoClass . '">' . e($reporte->tipo_reporte) . '</span></h4>
        </div>
        <div class="reporte-body">
            <div class="reporte-meta">
                <div class="reporte-meta-item">
                    <div class="reporte-meta-label">Colonia</div>
                    <div class="reporte-meta-value">' . $coloniaNombre . '</div>
                </div>
                <div class="reporte-meta-item">
                    <div class="reporte-meta-label">Ubicación</div>
                    <div class="reporte-meta-value">' . ($reporte->ubicacion ? e($reporte->ubicacion) : 'No especificada') . '</div>
                </div>
                <div class="reporte-meta-item">
                    <div class="reporte-meta-label">Fecha</div>
                    <div class="reporte-meta-value">' . $fecha . '</div>
                </div>
                <div class="reporte-meta-item">
                    <div class="reporte-meta-label">Encuesta ID</div>
                    <div class="reporte-meta-value">#' . $reporte->encuesta_id . '</div>
                </div>
            </div>
            <div class="reporte-descripcion">
                <strong>Descripción:</strong><br>
                ' . nl2br(e($reporte->descripcion)) . '
            </div>
        </div>
    </div>';
            }
        }

        $html .= '
    <div class="footer">
        <p>Este documento fue generado automáticamente por el Sistema de Presupuesto Participativo 2026</p>
        <p>Total de reportes incluidos: ' . $totalReportes . '</p>
    </div>
</body>
</html>';

        return $html;
    }
}
