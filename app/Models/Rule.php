<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'normas';
    protected $fillable = [
        'norma',
        'descripcion',
        'tipo',
        'cesavedac',
        'aguas',
        'enlistar_parametros',
    ];
    protected $casts = [
        'cesavedac' => 'boolean',
        'aguas' => 'boolean',
        'enlistar_parametros' => 'boolean',
    ];

    public $timestamps = false;

    use HasFactory;

    public function parametersCombinations ()
    {
        return $this->belongsToMany(ParameterCombination::class, 'normas_combinaciones_parametros_aguas', 'id_combinacion_parametro', 'id_norma');
    }
}
