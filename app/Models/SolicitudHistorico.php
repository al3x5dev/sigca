<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudHistorico extends Model
{
     use HasFactory;

    protected $table = 'SolicitudesHistorico';

    protected $fillable = [
        'id',
        'id_solicitud',
        'estado',
        'fecha',
    ];
}
