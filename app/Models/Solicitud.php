<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'id',
        'numero',
        'id_usuario',
        'id_comprador',
        'fecha',
    ];

    //public $timestamps = false;
}
