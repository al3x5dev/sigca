<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
     use HasFactory;

    protected $table = 'ProductosSolicitud';

    protected $fillable = [
        'id',
        'id_solicitud',
        'id_producto',
        'descripcion',
        'cant_solicitada',
        'cant_recibida',
    ];
}
