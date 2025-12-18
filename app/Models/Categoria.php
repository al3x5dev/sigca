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

    public function usuario(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'CompradoresCategorias', 'id_comprador', 'id_categoria');
    }

    public function solicitud(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'tipo');
    }
}
