<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public $timestamps = false;

    public function estado() : BelongsTo
    {
        return $this->belongsTo(Estado::class,'estado');
    }

    public function solicitud() : BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud');
    }
}
