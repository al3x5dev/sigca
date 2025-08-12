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
        Schema::create('CompradoresCategorias', function (Blueprint $table) {
            $table->integer('id_comprador')->nullable(false);
            $table->bigInteger('id_categoria')->nullable(false);

            $table->foreign('id_comprador')->references('id')->on('Usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_categoria')->references('id')->on('Categorias')->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['id_comprador', 'id_categoria']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CompradoresCategorias');
    }
};
