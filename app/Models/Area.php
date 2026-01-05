<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $connection = 'une_2316a_int';
    protected $table = 'PUENTE.dbo.vECO_areasnomina';

    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * Desactiva la asignación masiva por seguridad, ya que es de solo lectura.
     */
    protected $guarded = [];
}
