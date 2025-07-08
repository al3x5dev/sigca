<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ProductosSolicitud', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_solicitud')->nullable(false);
            $table->char('id_producto',20)->unique()->nullable();
            $table->string('descripcion',255)->nullable(false);
            $table->integer('cant_solicitada')->nullable(false);
            $table->integer('cant_recibida')->default(0);
            
            $table->foreign('id_solicitud')->references('id')->on('Solicitudes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ProductosSolicitud');
    }
};
