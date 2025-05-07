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
        DB::statement("CREATE VIEW vw_ProductosNuevos AS
        SELECT
            pe.Id_Producto,
            pe.Desc_Producto,
            pe.UM_Almacen,
            pe.Existencia_Actual,
            pe.Id_Almacen,
            pe.Fecha_Entrada
        FROM UNE_2316A_INT.dbo.vw_SIGCA_ProductosExistencia AS pe
        INNER JOIN dbo.Sync AS s ON pe.Fecha_Entrada > DATEADD(DAY, - 1, s.fecha)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_ProductosNuevos");
    }
};
