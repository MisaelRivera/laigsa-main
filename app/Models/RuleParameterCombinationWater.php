<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleParameterCombinationWater extends Model
{
    use HasFactory;
    protected $table = 'normas_combinaciones_parametros_aguas';
    public $timestamps = false; 

    public function rule ()
    {
        return $this->belongsTo(Rule::class, 'id_norma', 'id');
    }

    public function parametro ()
    {
        return $this->hasOneThrough(Parameter::class, ParameterCombination::class, 'id', 'id', 'id_combinacion_parametro', 'id_parametro');
    }

    public function unidad ()
    {
        return $this->hasOneThrough(Unit::class, ParameterCombination::class, 'id', 'id', 'id_combinacion_parametro', 'id_unidad');
    }

    public function metodo ()
    {
        return $this->hasOneThrough(Method::class, ParameterCombination::class, 'id', 'id_metodo', 'id_combinacion_parametro', 'id_metodo');
    }
    
}
