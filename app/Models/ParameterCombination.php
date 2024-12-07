<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterCombination extends Model
{
    use HasFactory;
    protected $table = "combinaciones_parametros";
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'subcontratado' => 'boolean', // Casts time to Carbon instance,
        'compuesto' => 'boolean',
        'ema' => 'boolean',
        'cna' => 'boolean',
        'ssa' => 'boolean',
        'supervisar' => 'boolean',
        'fecha_resultados_final' => 'boolean',
        'parametro_campo' => 'boolean',
        'incertidumbre' => 'boolean',
    ];

    public function parametro ()
    {
        return $this->belongsTo(Parameter::class, 'id_parametro', 'id');
    }

    public function unidad ()
    {
        return $this->belongsTo(Unit::class, 'id_unidad', 'id');
    }

    public function metodo ()
    {
        return $this->belongsTo(Method::class, 'id_metodo', 'id_metodo');
    }

    public function lcp ()
    {
        return $this->belongsTo(Lcp::class, 'id_lcp', 'id');
    }

    public function rules ()
    {
        return $this->belongsToMany(Rule::class, 'normas_combinaciones_parametros_aguas', 'id_norma', 'id_combinacion_parametro');
    }

    
}
