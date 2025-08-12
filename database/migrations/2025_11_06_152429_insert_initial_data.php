<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insertar roles
        DB::statement("INSERT INTO Roles (rol) VALUES
        ('Supervisor'),
        ('Comprador'),
        ('Usuario')
        ");


        // Insertar estados
        DB::statement("INSERT INTO Estados (estado) VALUES
        ('Pendiente'),
        ('En Proceso'),
        ('Completada'),
        ('Cancelada')");

        //Insertar categorias
        DB::statement("INSERT INTO Categorias (tipo) VALUES
        ('Transporte'),
        ('Prod. Químicos'),
        ('Electrónica, Informática'),
        ('Gases'),
        ('Materiales de oficina'),
        ('Mtto. y Averías')");


        // Insertar en Sync con valor por defecto
        DB::statement("INSERT INTO Sync (fecha) VALUES (DEFAULT);");

        // Insertar superuser
        $pass = Hash::make(env('APP_ADMIN_PASS', 'mypass'));

        DB::statement(
            "INSERT INTO Usuarios (id,nombre, cargo, usuario, password) VALUES (1,'SigcaAdmin', 'Administrador del Sistema', 'admin', '$pass')"
        );

        DB::statement("INSERT INTO Accesos (id_usuario,id_rol) VALUES (1,1)");


        // Ejecutar procedimiento almacenado sp_InsertarUsuario
        $usuarios = [
            [
                78892 => 'alejandrom',
                'roles' => [1, 2]
            ],
            [
                77212 => 'pepe',
                'roles' => [3]
            ],
            [
                78120 => 'mara',
                'roles' => [3]
            ],
            [
                78093 => 'arianna',
                'roles' => [3]
            ],
            [
                78797 => 'maxlenin',
                'roles' => [3]
            ],
            [
                78315 => 'mayle',
                'roles' => [3]
            ],
            [
                78866 => 'leisi',
                'roles' => [3]
            ],
            [
                77409 => 'junior',
                'roles' => [3]
            ],
            [
                77569 => 'jbravo',
                'roles' => [3]
            ],
            [
                78792 => 'islenia',
                'roles' => [3]
            ],
            [
                77565 => 'dania',
                'roles' => [1, 3]
            ],
            [
                77252 => 'odeyme',
                'roles' => [3]
            ],
            [
                78658 => 'luise',
                'roles' => [3]
            ],
            [
                78719 => 'daliana',
                'roles' => [3]
            ],
            [
                78378 => 'popi',
                'roles' => [3]
            ],
            [
                77321 => 'ivan',
                'roles' => [3]
            ],
            [
                77319 => 'yamile',
                'roles' => [3]
            ],
            [
                78913 => 'yanelis',
                'roles' => [3]
            ],
            [
                77763 => 'lidice',
                'roles' => [3]
            ],
            [
                78533 => 'montenegro',
                'roles' => [3]
            ],
            [
                77681 => 'hcepero',
                'roles' => [3]
            ],
            [
                78116 => 'madelin',
                'roles' => [3]
            ],
            [
                78594 => 'jasiel',
                'roles' => [3]
            ],
            [
                78558 => 'rafa',
                'roles' => [3]
            ],
            [
                77618 => 'zurita',
                'roles' => [3]
            ],
            [
                78845 => 'lianybet',
                'roles' => [3]
            ],
            [
                78366 => 'osdeny',
                'roles' => [3]
            ],
            [
                77789 => 'gilberto',
                'roles' => [3]
            ],
            [
                78496 => 'dayaisy',
                'roles' => [3]
            ],
            [
                78510 => 'jose',
                'roles' => [3]
            ],
            [
                78796 => 'aida',
                'roles' => [3]
            ],
            [
                60921 => 'cepero',
                'roles' => [3]
            ],
            [
                78575 => 'carlos',
                'roles' => [3]
            ],
            [
                78423 => 'milena',
                'roles' => [3]
            ],
            [
                78637 => 'reyna',
                'roles' => [3]
            ],
            [
                78934 => 'robertom',
                'roles' => [3]
            ],
            [
                78280 => 'rigoberto',
                'roles' => [3]
            ],
            [
                77824 => 'elania',
                'roles' => [3]
            ],
            [
                21266 => 'anastasio',
                'roles' => [3]
            ],
            [
                78275 => 'yunior',
                'roles' => [3]
            ],
            [
                78685 => 'isis',
                'roles' => [3]
            ],
            [
                78935 => 'yusmary',
                'roles' => [3]
            ],
            [
                77771 => 'eliexy',
                'roles' => [3]
            ],
            [
                78897 => 'liver',
                'roles' => [1, 2, 3]
            ],
            [
                78019 => 'emilio',
                'roles' => [2, 3]
            ],
            [
                74714 => 'mabel',
                'roles' => [2, 3]
            ],
            [
                78586 => 'midalmi',
                'roles' => [2, 3]
            ],
            [
                77669 => 'noel',
                'roles' => [2, 3]
            ],
            [
                78438 => 'lauremi',
                'roles' => [3]
            ],
            [
                78048 => 'nelson',
                'roles' => [3]
            ],
            [
                60676 => 'pita',
                'roles' => [3]
            ],
            [
                77566 => 'camacho',
                'roles' => [3]
            ],
            [
                77634 => 'eriel',
                'roles' => [3]
            ],
            [
                78715 => 'dariel',
                'roles' => [3]
            ],
            [
                78451 => 'juanc',
                'roles' => [3]
            ],
            [
                84555 => 'brenllas',
                'roles' => [3]
            ],
            [
                78507 => 'giraldo',
                'roles' => [3]
            ],
            [
                77986 => 'molembito',
                'roles' => [3]
            ],
            [
                78564 => 'omar',
                'roles' => [3]
            ],
            [
                77030 => 'efrain',
                'roles' => [3]
            ],
        ];
        foreach ($usuarios as $usuario) {
            foreach ($usuario as $key => $value) {
                if ($key != 'roles') {
                    $exp = $key;
                    $user = $value;
                } else {
                    $roles = implode(',', $value);
                }
            }
            DB::statement("EXEC sp_InsertarUsuario $exp, '$roles', $user;");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DELETE FROM Roles;");
        DB::statement("DELETE FROM Estados;");
        DB::statement("DELETE FROM Categorias;");
        DB::statement("DELETE FROM Sync;");
        DB::statement("DELETE FROM Usuarios;");
        DB::statement("DELETE FROM Accesos;");
    }
};
