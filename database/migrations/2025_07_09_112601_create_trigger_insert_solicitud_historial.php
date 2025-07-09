<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         // Crear el trigger
        DB::statement('
            CREATE TRIGGER insert_solicitudes_historica
            ON Solicitudes
            AFTER INSERT
            AS
            BEGIN
                INSERT INTO SolicitudesHistorico (id_solicitud, estado)
                SELECT INSERTED.id, 1
                FROM INSERTED;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         // Eliminar el trigger
        DB::statement('DROP TRIGGER IF EXISTS insert_solicitudes_historica');
    }
};
