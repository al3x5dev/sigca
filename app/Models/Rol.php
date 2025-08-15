<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'Roles';
    protected $fillable = ['id', 'rol'];
    public $timestamps = false;

    public function usuario() : BelongsToMany
    {
        return $this->belongsToMany(Usuario::class,'Accesos','id_rol','id_usuario');
    }
}
