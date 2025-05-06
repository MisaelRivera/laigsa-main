<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterCombinationAnalist extends Model
{
    use HasFactory;
    public $table = 'combinaciones_parametros_analistas';
    public $timestamps = false;
    protected $guarded = [];


    public function usuario ()
    {
        return $this->hasOne(User::class, 'id', 'id_usuario');
    }
}
