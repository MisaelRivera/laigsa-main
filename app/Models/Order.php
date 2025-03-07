<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;
    protected $table = 'ordenes';
    protected $dateFormat = 'H:i:s';
    public $timestamps = false;
    protected $fillable = [
        'folio',
        'id_cliente',
        'numero_muestras',
        'direccion_muestreo',
        'fecha_recepcion',
        'hora_recepcion',
        'aguas_alimentos',
        'cesavedac',
        'area_recepcion_muestras_limpia',
        'observaciones',
        'temperatura',
        'numero_cotizacion',
        'numero_termometro',
    ];

    protected $casts = [
        'cesavedac' => 'boolean',
        'area_recepcion_muestras_limpia' => 'boolean',
    ];

    protected function horaRecepcion():Attribute {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::createFromFormat('H:i:s', $value)->format('H:i'):null
        );
    }

    public function cliente ()
    {
        return $this->belongsTo(Client::class, 'id_cliente', 'id');
    }

    public function siralab () {
        return $this->hasOne(Siralab::class, 'id_orden');
    }

    /*public function muestras ()
    {
        if ($this->aguas_alimentos === 'Aguas') {
            return $this->hasMany(WaterSample::class, 'id_orden');
        } else {
            return $this->hasMany(FoodSample::class, 'id_orden');
        }
    }*/

    public function muestras_aguas () {
        return $this->hasMany(WaterSample::class, 'id_orden');
    }

    public function muestras_alimentos () {
        return $this->hasMany(FoodSample::class, 'id_orden');
    }

    /*protected function supervision (): Attribute {
        return Attribute::make(
            get: function ($value) {
               $result = DB::table('ordenes')
                ->selectRaw("IF((SELECT cesavedac FROM ordenes WHERE id = ?) = 1 OR (SELECT COUNT(id) FROM muestras WHERE muestras.id_orden = ? AND muestras.muestreador = 'Cliente') > 0, true, false) as result", [$this->attributes['id'], $this->attributes['id']])
                ->first();
                if ((int)$result->result === 1) {
                    return false;
                } else {
                    return $value;
                }
            }
        );
    } */

    public static function getOrderWithLastFolio()
    {
        return static::with(['cliente'])
            ->orderBy('fecha_recepcion', 'desc')
            ->orderBy('hora_recepcion', 'desc')
            ->orderBy('folio', 'desc')->first();
    }
}
