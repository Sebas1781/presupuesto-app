<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObraPublica extends Model
{
    protected $table = 'obras_publicas';

    protected $fillable = [
        'colonia_id',
        'nombre',
        'descripcion',
    ];

    public function colonia(): BelongsTo
    {
        return $this->belongsTo(Colonia::class);
    }
}
