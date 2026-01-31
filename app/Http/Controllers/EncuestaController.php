<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Colonia;
use App\Models\Encuesta;
use App\Models\ObraPublica;
use App\Models\Propuesta;
use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EncuestaController extends Controller
{
    public function create()
    {
        $colonias = Colonia::with('obrasPublicas')->get();
        return view('encuesta.create-animated', compact('colonias'));
    }

    public function getObrasByColonia($coloniaId)
    {
        $obras = ObraPublica::where('colonia_id', $coloniaId)->get();
        return response()->json($obras);
    }

    public function store(Request $request)
    {
        // Verificar si la colonia tiene obras disponibles
        $colonia = Colonia::with('obrasPublicas')->find($request->colonia_id);
        $tieneObras = $colonia && $colonia->obrasPublicas->count() > 0;

        // Preparar reglas de validación base
        $rules = [
            'colonia_id' => 'required|exists:colonias,id',
            'genero' => 'required|string',
            'edad' => 'required|integer|min:1|max:120',
            'nivel_educativo' => 'required|string',
            'estado_civil' => 'required|string',
            'desea_reporte' => 'boolean',
            'tipo_reporte' => 'nullable|string',
            'descripcion_reporte' => 'nullable|string',
            'evidencia_reporte' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'ubicacion_reporte' => 'nullable|string',
            'propuestas' => 'nullable|array|max:2',
            'propuestas.*.tipo_propuesta' => 'required_with:propuestas|string',
            'propuestas.*.nivel_prioridad' => 'required_with:propuestas|string',
            'propuestas.*.personas_beneficiadas' => 'required_with:propuestas|string',
            'propuestas.*.fotografia' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'propuestas.*.ubicacion' => 'required_with:propuestas|string',
            'propuestas.*.descripcion_breve' => 'required_with:propuestas|string',
        ];

        // Solo requerir calificación de obras si la colonia tiene obras
        if ($tieneObras) {
            $rules['obras_calificadas'] = 'required|array';
        }

        $request->validate($rules);

        // Crear encuesta
        $encuesta = Encuesta::create([
            'colonia_id' => $request->colonia_id,
            'genero' => $request->genero,
            'edad' => $request->edad,
            'nivel_educativo' => $request->nivel_educativo,
            'estado_civil' => $request->estado_civil,
            'obras_calificadas' => $tieneObras ? $request->obras_calificadas : null,
            'desea_reporte' => $request->boolean('desea_reporte'),
        ]);

        // Crear propuestas si existen
        if ($request->has('propuestas')) {
            foreach ($request->propuestas as $propuestaData) {
                // Validar que tenga datos mínimos requeridos
                if (empty($propuestaData['tipo_propuesta']) || empty($propuestaData['nivel_prioridad'])) {
                    continue;
                }

                $fotografiaPath = null;
                if (isset($propuestaData['fotografia']) && $propuestaData['fotografia']) {
                    $fotografiaPath = $propuestaData['fotografia']->store('propuestas', 'public');
                }

                Propuesta::create([
                    'encuesta_id' => $encuesta->id,
                    'tipo_propuesta' => $propuestaData['tipo_propuesta'],
                    'nivel_prioridad' => $propuestaData['nivel_prioridad'],
                    'personas_beneficiadas' => $propuestaData['personas_beneficiadas'] ?? 0,
                    'fotografia' => $fotografiaPath,
                    'ubicacion' => $propuestaData['ubicacion'] ?? null,
                    'descripcion_breve' => $propuestaData['descripcion_breve'] ?? null,
                ]);
            }
        }

        // Crear reportes si existen
        if ($request->boolean('desea_reporte') && $request->has('reportes')) {
            foreach ($request->reportes as $reporteData) {
                // Validar que tenga datos mínimos requeridos
                if (empty($reporteData['tipo_reporte']) || empty($reporteData['descripcion'])) {
                    continue;
                }

                $evidenciaPath = null;
                if (isset($reporteData['evidencia']) && $reporteData['evidencia']) {
                    $evidenciaPath = $reporteData['evidencia']->store('reportes', 'public');
                }

                Reporte::create([
                    'encuesta_id' => $encuesta->id,
                    'tipo_reporte' => $reporteData['tipo_reporte'],
                    'descripcion' => $reporteData['descripcion'],
                    'evidencia' => $evidenciaPath,
                    'ubicacion' => $reporteData['ubicacion'] ?? null,
                ]);
            }
        }

        return redirect()->route('encuesta.success')->with('success', 'Encuesta enviada correctamente. ¡Gracias por tu participación!');
    }

    public function success()
    {
        return view('encuesta.success');
    }
}
