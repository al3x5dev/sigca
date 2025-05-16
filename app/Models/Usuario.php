<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    public static function create($data)
    {
        return DB::select('CALL sp_InsertarUsuario(?, ?, ?)', [
            $data['exp'],
            $data['rol'],
            $data['usuario']
        ]);
    }
}
