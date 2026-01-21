<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;
    protected $table = 'Categorias';
    protected $fillable = ['tipo'];

    public function compradores(): BelongsToMany
    {
        return $this->belongsToMany(
            Usuario::class,
            'CompradoresCategorias',
            'id_categoria',   // Foreign key en pivot que referencia a Categoria
            'id_comprador',   // Foreign key en pivot que referencia a Usuario
            'id',             // Local key en Categoria
            'id'              // Local key en Usuario
        );
    }

    public function solicitud(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'tipo');
    }
}
