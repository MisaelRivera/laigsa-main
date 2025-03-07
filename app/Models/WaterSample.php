<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WaterSample extends Model
{
    protected $table = 'muestras_aguas';
    protected $fillable = [
        'tipo_muestra',
        'id_identificacion_muestra',
        'caracteristicas',
        'numero_muestra',
        'id_orden',
        'muestreador',
        'pH',
        'tratada_biologicamente',
        'cloro',
        'valor_cloro',
        'ph_cromo_hexavalente',
        'tipo_muestreo',
        'conductividad',
        'fecha_muestreo',
        'hora_muestreo',
        'fecha_final_muestreo',
        'hora_final_muestreo',
        'fecha_composicion',
        'hora_composicion',
        'flujo_1',
        'flujo_2',
        'flujo_3',
        'flujo_4',
        'flujo_5',
        'flujo_6',
        'parametros',
        'otros_parametros',
        'preservacion_correcta'
    ];

    protected $dateFormat = 'H:i:s';

    protected $casts = [
        'tratada_biologicamente' => 'boolean',
        'siralab' => 'boolean',
        'otros_parametros' => 'boolean'
    ];

    protected function tipoMuestreo (): Attribute {
        return Attribute::make(
            get: function ($value) {
               return str_replace('_', ' ', $value);
            }
        );
    }

    protected function horaMuestreo ():Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::createFromFormat('H:i:s', $value)->format('H:i'):null
        );
    }

    protected function horaFinalMuestreo ():Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::createFromFormat('H:i:s', $value)->format('H:i'):null
        );
    }

    protected function horaComposicion ():Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::createFromFormat('H:i:s', $value)->format('H:i'):null
        );
    }

    public $timestamps = false;
    use HasFactory;

    public function orden ()
    {
        return $this->belongsTo(Order::class, 'id_orden');
    }

    public function identificacionMuestraRelacion ()
    {   
        return $this->hasOne(SampleIdentification::class, 'id', 'id_identificacion_muestra');
    }
}
