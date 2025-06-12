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
        Schema::create('Notificaciones', function (Blueprint $table) {
            $table->id();
            $table->integer('id_usuario')->nullable(false);
            $table->string('mensaje',255)->nullable(false);
            $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('id_usuario')->references('id')->on('Usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Notificaciones');
    }
};
