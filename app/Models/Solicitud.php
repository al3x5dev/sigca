<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'Solicitudes';

    protected $fillable = [
        'numero',
        'id_usuario',
        'id_comprador',
        'categoria',
        'area',
        'ccosto',
        'prioridad',
        'detalles',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'area' => 'string',      // <-- ¡AÑADE ESTA LÍNEA!
        'ccosto' => 'string',    // <-- ¡Y ESTA TAMBIÉN!
        // 'detalles' es 'text' y no necesita cast, 'integer' tampoco si no quieres.
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

    public function historico(): HasMany
    {
        return $this->hasMany(SolicitudHistorico::class, 'id_solicitud');
    }

    public function ultimoEstado()
    {
        return $this->hasOne(SolicitudHistorico::class, 'id_solicitud')
            ->latest('fecha');
    }

    public function categoriaSolicitud(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria');
    }

    public function prioridadSolicitud(): BelongsTo
    {
        return $this->belongsTo(Prioridad::class, 'prioridad');
    }

    public function vwArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area');
    }

    /**
     * Obtiene los datos del centro de costo desde la vista correspondiente.
     */
    public function vwCcosto()
    {
        // Similar al anterior. Ajusta 'id_ccosto' al nombre de la columna
        // clave en tu vista 'vista_ccostos'.
        return $this->belongsTo(CentroCosto::class, 'ccosto', 'idcc');
    }
}
