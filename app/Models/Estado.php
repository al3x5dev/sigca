<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estado extends Model
{
    use HasFactory;
    protected $table='Estados';
    protected $fillable=['id','estado'];

    public function historico() : HasMany
    {
        return $this->hasMany(SolicitudHistorico::class,'estado');
    }
}
