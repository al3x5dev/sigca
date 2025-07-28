<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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


        // Insertar en Sync con valor por defecto
        DB::statement("INSERT INTO Sync (fecha) VALUES (DEFAULT);");


        // Ejecutar procedimiento almacenado sp_InsertarUsuario
        DB::statement("EXEC sp_InsertarUsuario '78892', 2, 'alejandrom';");
        DB::statement("EXEC sp_InsertarUsuario '77565', 3, 'dania';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DELETE FROM Roles");
        DB::statement("DELETE FROM Estados");
        DB::statement("DELETE FROM Sync;");
        DB::statement("DELETE FROM Usuarios;");
    }
};
