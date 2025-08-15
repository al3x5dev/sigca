<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Acceso extends Model
{
    protected $table = 'Accesos';

    // Desactiva la autoincrementación del ID
    public $incrementing = false;

    // Define las claves primarias compuestas
    protected $primaryKey = ['id_usuario', 'id_rol'];

    // Define las claves foráneas
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}
