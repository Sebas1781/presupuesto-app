<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Colonia extends Model
{
    protected $fillable = [
        'nombre',
        'distrito',
        'descripcion',
    ];

    public function obrasPublicas(): HasMany
    {
        return $this->hasMany(ObraPublica::class);
    }

    public function encuestas(): HasMany
    {
        return $this->hasMany(Encuesta::class);
    }
}
