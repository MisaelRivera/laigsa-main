<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Method extends Model
{
    use HasFactory;
    protected $table = 'metodos';
    protected $primaryKey = 'id_metodo';
    protected $fillable = ['nombre'];
    public $timestamps = false;
}
