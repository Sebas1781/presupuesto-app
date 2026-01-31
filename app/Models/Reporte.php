<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reporte extends Model
{
    protected $fillable = [
        'encuesta_id',
        'tipo_reporte',
        'descripcion',
        'evidencia',
        'ubicacion',
    ];

    public function encuesta(): BelongsTo
    {
        return $this->belongsTo(Encuesta::class);
    }
}
