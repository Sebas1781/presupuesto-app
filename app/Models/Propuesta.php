<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Propuesta extends Model
{
    protected $fillable = [
        'encuesta_id',
        'tipo_propuesta',
        'nivel_prioridad',
        'personas_beneficiadas',
        'fotografia',
        'ubicacion',
        'descripcion_breve',
    ];

    public function encuesta(): BelongsTo
    {
        return $this->belongsTo(Encuesta::class);
    }
}
