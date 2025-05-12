<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterCombinationSupervisor extends Model
{
    use HasFactory;
    public $table = 'combinaciones_parametros_supervisores';
    public $timestamps = false;
    protected $guarded = [];

    public function usuarios ()
    {
        return $this->hasMany(User::class, 'id', 'id_usuario');
    }

    public function parameterCombination ()
    {
        return $this->hasMany(ParameterCombination::class, 'id', 'id_combinacion_parametro');
    }
    
    public function usuariosIds ()
    {
        return $this->usuarios()->map(function ($usuario) {
            return ['id' => $usuario->id];
        });
    } 
}
