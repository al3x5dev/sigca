<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Usuario extends Model
{
    use HasFactory;
    // Definir la tabla 
    protected $table = 'usuarios';

    // Definir los campos que se pueden llenar
    protected $fillable = [
        'nombre',
        'cargo',
        'rol',
        'usuario',
        'ultm_acc',
        'activo'
    ];

    // Deshabilitar timestamps si no los necesitas
    public $timestamps = false;

    /**
     * Método para insertar un nuevo usuario usando un procedimiento almacenado
     */
    public static function nuevo($data)
    {
        return DB::select('CALL sp_InsertarUsuario(?, ?, ?)', [
            $data['exp'],
            $data['rol'],
            $data['usuario']
        ]);
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol');
    }

    public function notificacion(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_usuario');
    }
    
    public function perfil() : HasOne
    {
        return $this->hasOne(Perfil::class,'id');
    }

    public function solicitudCreada(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'id_usuario');
    }

    public function solicitudAtendida(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'id_comprador');
    }
}
