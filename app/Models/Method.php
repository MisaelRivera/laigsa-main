<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Method extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'metodos';
    protected $primaryKey = 'id_metodo';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'id_metodo'; // This tells Laravel to use 'method_id' instead of 'id'
    }
}
