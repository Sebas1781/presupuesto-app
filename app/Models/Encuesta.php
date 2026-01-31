<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Encuesta extends Model
{
    protected $fillable = [
        'colonia_id',
        'genero',
        'edad',
        'nivel_educativo',
        'estado_civil',
        'obras_calificadas',
        'desea_reporte',
        'tipo_reporte',
        'descripcion_reporte',
        'evidencia_reporte',
        'ubicacion_reporte',
    ];

    protected $casts = [
        'obras_calificadas' => 'array',
        'desea_reporte' => 'boolean',
    ];

    public function colonia(): BelongsTo
    {
        return $this->belongsTo(Colonia::class);
    }

    public function propuestas(): HasMany
    {
        return $this->hasMany(Propuesta::class);
    }

    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class);
    }
}
