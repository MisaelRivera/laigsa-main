<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParamDescription extends Model
{
    use HasFactory;

    public $table = 'descripcion_parametros';
}
