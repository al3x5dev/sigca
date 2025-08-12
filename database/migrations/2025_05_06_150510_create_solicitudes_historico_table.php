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
        Schema::create('SolicitudesHistorico', function (Blueprint $table) {
            $table->bigInteger('id_solicitud')->nullable(false);
            $table->integer('estado')->nullable(false);
            $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->foreign('id_solicitud')->references('id')->on('Solicitudes')->onDelete('cascade');
            $table->foreign('estado')->references('id')->on('Estados')->onDelete('no action');

            $table->primary(['id_solicitud','estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SolicitudesHistorico');
    }
};
