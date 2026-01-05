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
        'id_solicitud',
        'estado',
        'id_usuario',
        'fecha',
    ];

    // Deshabilitar la clave primaria automática
    public $incrementing = false;

    // Definir las columnas de la clave primaria compuesta
    protected $primaryKey = ['id_solicitud', 'estado'];

    // Deshabilitar los timestamps
    public $timestamps = false;

    public function estadoDetalles() : BelongsTo
    {
        return $this->belongsTo(Estado::class,'estado');
    }

    public function solicitud() : BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
