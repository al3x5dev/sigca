<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $fillable = ['id', 'rol'];
    public $timestamps = false;

    public function usuario() : HasMany
    {
        return $this->hasMany(Usuario::class,'rol');
    }
}
