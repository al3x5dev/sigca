<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'nuevo'
    ];

    public $timestamps = false;

    public function solicitud() : BelongsTo
    {
        return $this->belongsTo(Solicitud::class,'id_solicitud');
    }
}
