<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EncuestasExport implements FromCollection, WithHeadings, WithMapping
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
            'Obras Calificadas',
            'Desea Reporte',
            'Total Propuestas',
            'Total Reportes',
            'Fecha Creación'
        ];
    }

    public function map($encuesta): array
    {
        return [
            $encuesta->id,
            $encuesta->colonia->nombre ?? 'Sin colonia',
            $encuesta->genero,
            $encuesta->edad,
            $encuesta->nivel_educativo,
            $encuesta->estado_civil,
            is_array($encuesta->obras_calificadas) ? json_encode($encuesta->obras_calificadas) : $encuesta->obras_calificadas,
            $encuesta->desea_reporte ? 'Sí' : 'No',
            $encuesta->propuestas->count(),
            $encuesta->reportes->count(),
            $encuesta->created_at->format('Y-m-d H:i:s')
        ];
    }
}
