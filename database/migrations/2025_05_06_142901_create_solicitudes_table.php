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
        Schema::create('Prioridades', function (Blueprint $table) {
            $table->id();
            $table->string('prioridad')->nullable(false)->unique();
        });

        Schema::create('Solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 10)->nullable(false)->unique();
            $table->integer('id_usuario')->nullable(false);
            $table->integer('id_comprador')->nullable(true);
            $table->integer('categoria')->nullable(false); // Añadir la columna 'categoria'
            $table->foreignId('prioridad')->references('id')->on('Prioridades')->onDelete('cascade');
            $table->text('notas');
            $table->dateTime('fecha')->useCurrent();

            $table->foreign('categoria')->references('id')->on('Categorias')->onDelete('no action');
            $table->foreign('id_usuario')->references('id')->on('Usuarios')->onDelete('no action');
            $table->foreign('id_comprador')->references('id')->on('Usuarios')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Prioridades');
        Schema::dropIfExists('Solicitudes');
    }
};
