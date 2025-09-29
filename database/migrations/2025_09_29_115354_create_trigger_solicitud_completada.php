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
        DB::statement("
            CREATE TRIGGER solicitd_completada
            ON ProductosSolicitud
            AFTER UPDATE
            AS
            BEGIN
                SET NOCOUNT ON;
            
                -- Declarar variables para almacenar la id_solicitud y el resultado de la verificación
                DECLARE @id_solicitud bigint;
                DECLARE @producto_count int;
                DECLARE @producto_match_count int;

                -- Obtener la id_solicitud de la fila actualizada
                SELECT @id_solicitud = id_solicitud FROM inserted;

                -- Contar el número total de productos para la solicitud
                SELECT @producto_count = COUNT(*)
                FROM SIGCA_DB.dbo.ProductosSolicitud
                WHERE id_solicitud = @id_solicitud;

                -- Contar el número de productos donde la cantidad solicitada es igual a la cantidad recibida
                SELECT @producto_match_count = COUNT(*)
                FROM SIGCA_DB.dbo.ProductosSolicitud
                WHERE id_solicitud = @id_solicitud AND cant_solicitada = cant_recibida;

                -- Verificar si todos los productos tienen la cantidad solicitada igual a la cantidad recibida
                IF @producto_count = @producto_match_count
                    BEGIN

                    -- Insertar en la tabla SolicitudesCompletas
                    INSERT INTO SIGCA_DB.dbo.SolicitudesHistorico (id_solicitud,estado)
                    VALUES (@id_solicitud,3);
                END
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DROP TRIGGER IF EXISTS solicitd_completada');
    }
};
