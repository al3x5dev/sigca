<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prioridad extends Model
{
    use HasFactory;
    protected $table = 'Prioridades';
    protected $fillable = ['tipo'];

    public function solicitud() : HasMany
    {
        return $this->hasMany(Solicitud::class, 'prioridad');
    }
}
