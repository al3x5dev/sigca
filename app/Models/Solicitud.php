<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public $timestamps = false;

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function comprador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_comprador');
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'id_solicitud');
    }

    public function historico() : HasMany
    {
        return $this->hasMany(SolicitudHistorico::class, 'id_solicitud');
    }
}
