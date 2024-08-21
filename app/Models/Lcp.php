<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lcp extends Model
{
    use HasFactory;
    protected $table = 'lcps';
    protected $fillable = ['valor', 'id_parametro']; 
    public $timestamps = false;

    public function parameter ()
    {
        return $this->belongsTo(Parameter::class, 'id_parametro', 'id');
    }
}
