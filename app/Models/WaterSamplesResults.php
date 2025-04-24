<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSamplesResults extends Model
{
    use HasFactory;
    protected $table = 'resultados_muestras_aguas';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'es_compuesto' => 'boolean',
        'ema' => 'boolean',
        'cna' => 'boolean',
        'ssa' => 'boolean',
        'tiene_supervision' => 'boolean',
        'tiene_fecha_resultado_final' => 'boolean',
        'es_parametro_campo' => 'boolean',
        'tiene_incertidumbre' => 'boolean',
    ];

    public function muestra_agua ()
    {
        return $this->belongsTo(WaterSample::class, 'id', 'id_muestra');
    }
}
