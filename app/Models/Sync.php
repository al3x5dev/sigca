<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sync extends Model
{
    protected $table = 'Sync';
    protected $fillable = 'fecha';
    public $incrementing = false;
    public $timestamps = false;
}
