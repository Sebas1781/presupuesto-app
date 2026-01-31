<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class EncuestasDetalladoExport implements WithMultipleSheets
{
    protected $encuestas;

    public function __construct(Collection $encuestas)
    {
        $this->encuestas = $encuestas;
    }

    public function sheets(): array
    {
        return [
            new EncuestasSheet($this->encuestas),
            new PropuestasSheet($this->encuestas),
            new ReportesSheet($this->encuestas),
        ];
    }
}

class EncuestasSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $encuestas;

    public function __construct(Collection $encuestas)
    {
        $this->encuestas = $encuestas;
    }

    public function title(): string
    {
        return 'Encuestas';
    }

    public function collection()
    {
        return $this->encuestas;
    }

    public function headings(): array
    {
        return [
            'ID Encuesta',
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
            $encuesta->colonia ? $encuesta->colonia->nombre : 'Sin colonia',
            $encuesta->genero,
            $encuesta->edad,
            $encuesta->nivel_educativo,
            $encuesta->estado_civil,
            is_array($encuesta->obras_calificadas) ? json_encode($encuesta->obras_calificadas) : ($encuesta->obras_calificadas ?? 'N/A'),
            $encuesta->desea_reporte ? 'Sí' : 'No',
            $encuesta->propuestas ? $encuesta->propuestas->count() : 0,
            $encuesta->reportes ? $encuesta->reportes->count() : 0,
            $encuesta->created_at ? $encuesta->created_at->format('Y-m-d H:i:s') : 'N/A'
        ];
    }
}

class PropuestasSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $encuestas;

    public function __construct(Collection $encuestas)
    {
        $this->encuestas = $encuestas;
    }

    public function title(): string
    {
        return 'Propuestas';
    }

    public function collection()
    {
        return $this->encuestas->flatMap(function ($encuesta) {
            return $encuesta->propuestas->map(function ($propuesta) use ($encuesta) {
                $propuesta->encuesta = $encuesta;
                return $propuesta;
            });
        });
    }

    public function headings(): array
    {
        return [
            'ID Propuesta',
            'ID Encuesta',
            'Colonia',
            'Tipo Propuesta',
            'Nivel Prioridad',
            'Personas Beneficiadas',
            'Ubicación',
            'Descripción',
            'Fotografía',
            'Fecha Creación'
        ];
    }
//aaaaaa
    public function map($propuesta): array
    {
        return [
            $propuesta->id,
            $propuesta->encuesta_id,
            $propuesta->encuesta && $propuesta->encuesta->colonia ? $propuesta->encuesta->colonia->nombre : 'Sin colonia',
            $propuesta->tipo_propuesta,
            $propuesta->nivel_prioridad,
            $propuesta->personas_beneficiadas,
            $propuesta->ubicacion,
            $propuesta->descripcion_breve,
            $propuesta->fotografia ? 'Sí' : 'No',
            $propuesta->created_at ? $propuesta->created_at->format('Y-m-d H:i:s') : 'N/A'
        ];
    }
}

class ReportesSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $encuestas;

    public function __construct(Collection $encuestas)
    {
        $this->encuestas = $encuestas;
    }

    public function title(): string
    {
        return 'Reportes';
    }

    public function collection()
    {
        return $this->encuestas->flatMap(function ($encuesta) {
            return $encuesta->reportes->map(function ($reporte) use ($encuesta) {
                $reporte->encuesta = $encuesta;
                return $reporte;
            });
        });
    }

    public function headings(): array
    {
        return [
            'ID Reporte',
            'ID Encuesta',
            'Colonia',
            'Tipo Reporte',
            'Descripción',
            'Ubicación',
            'Evidencia',
            'Fecha Creación'
        ];
    }

    public function map($reporte): array
    {
        return [
            $reporte->id,
            $reporte->encuesta_id,
            $reporte->encuesta && $reporte->encuesta->colonia ? $reporte->encuesta->colonia->nombre : 'Sin colonia',
            $reporte->tipo_reporte,
            $reporte->descripcion,
            $reporte->ubicacion,
            $reporte->evidencia ? 'Sí' : 'No',
            $reporte->created_at ? $reporte->created_at->format('Y-m-d H:i:s') : 'N/A'
        ];
    }
}
