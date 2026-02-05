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
        // Campos de Seguridad Pública
        'servicio_seguridad',
        'confia_policia',
        'horario_inseguro',
        'problemas_seguridad',
        'lugares_seguros',
        'emergencia_transporte',
        'caminar_noche',
        'hijos_solos',
        'transporte_publico',
    ];

    protected $casts = [
        'obras_calificadas' => 'array',
        'desea_reporte' => 'boolean',
        'problemas_seguridad' => 'array',
        'lugares_seguros' => 'array',
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
