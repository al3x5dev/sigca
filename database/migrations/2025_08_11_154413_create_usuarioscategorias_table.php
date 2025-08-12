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
        Schema::create('UsuariosCategorias', function (Blueprint $table) {
            $table->integer('id_usuario')->nullable(false);
            $table->bigInteger('id_categoria')->nullable(false);

            $table->foreign('id_usuario')->references('id')->on('Usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_categoria')->references('id')->on('Categorias')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('UsuariosCategorias');
    }
};
