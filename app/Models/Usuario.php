<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Usuario extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    // Definir la tabla 
    protected $table = 'Usuarios';

    // Definir los campos que se pueden llenar
    protected $fillable = [
        'nombre',
        'cargo',
        'usuario',
        'password',
        'ultm_acc',
        'activo'
    ];

    protected $hidden = ['password'];

    // Deshabilitar timestamps si no los necesitas
    public $timestamps = false;

    /**
     * Método para insertar un nuevo usuario usando un procedimiento almacenado
     */
    public static function nuevo($data)
    {
        return DB::select('CALL sp_InsertarUsuario(?, ?, ?)', [
            $data['exp'],
            '' . implode(',', $data['rol']) . '',
            $data['usuario']
        ]);
    }

    public function rol(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'Accesos', 'id_usuario', 'id_rol');
    }

    public function categoria(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'CompradoresCategorias', 'id_rol', 'id_usuario');
    }

    public function notificacion(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_usuario');
    }

    public function perfil(): HasOne
    {
        return $this->hasOne(Perfil::class, 'id');
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
