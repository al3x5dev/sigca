<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompradorXCategoria extends Model
{
    use HasFactory;
    protected $table = 'CompradoresCategorias';
    protected $primaryKey = [
        'id_comprador',
        'id_categoria'
    ];
    public $incrementing = false;

    public function comprador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_comprador');
    }
}
