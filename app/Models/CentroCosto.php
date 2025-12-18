<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCosto extends Model
{
    use HasFactory;

    protected $connection = 'une_2316a_int';
    protected $table = 'PUENTE.dbo.vECO_ccosto';
}
