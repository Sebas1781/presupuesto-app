<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class EncuestasBasicExport implements FromCollection
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
}