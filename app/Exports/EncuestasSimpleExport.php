<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EncuestasSimpleExport implements FromCollection, WithHeadings, WithMapping
{
    protected $encuestas;

    public function __construct(Collection $encuestas)
    {
        $this->encuestas = $encuestas;
    }

    public function collection()
    {
        return $this->encuestas;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Colonia',
            'Género',
            'Edad',
            'Nivel Educativo',
            'Estado Civil',
            'Calificación Obras',
            'Desea Reporte',
            'Total Propuestas',
            'Total Reportes',
            'Fecha'
        ];
    }

    public function map($encuesta): array
    {
        $obrasCalificadas = '';
        if (is_array($encuesta->obras_calificadas)) {
            foreach ($encuesta->obras_calificadas as $obraId => $calificacion) {
                $obrasCalificadas .= "Obra $obraId: $calificacion estrellas; ";
            }
        }

        return [
            $encuesta->id,
            $encuesta->colonia ? $encuesta->colonia->nombre : 'Sin colonia',
            $encuesta->genero,
            $encuesta->edad,
            $encuesta->nivel_educativo,
            $encuesta->estado_civil,
            trim($obrasCalificadas, '; ') ?: 'N/A',
            $encuesta->desea_reporte ? 'Sí' : 'No',
            $encuesta->propuestas ? $encuesta->propuestas->count() : 0,
            $encuesta->reportes ? $encuesta->reportes->count() : 0,
            $encuesta->created_at ? $encuesta->created_at->format('Y-m-d H:i:s') : 'N/A'
        ];
    }
}