<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categoria extends Model
{
    use HasFactory;
    protected $table='Categorias';
    protected $primaryKey=[
        'id',
        'tipo'
    ];
    public $incrementing=false;

    public function usuario() : BelongsToMany
    {
        return $this->belongsToMany(Usuario::class,'CompradoresCategorias','id_comprador','id_categoria');
    }
}
