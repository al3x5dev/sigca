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
        Schema::create('Accesos', function (Blueprint $table) {
            $table->integer('id_usuario')->nullable(false);
            $table->integer('id_rol')->nullable(false);

            $table->foreign('id_usuario')->references('id')->on('Usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_rol')->references('id')->on('Roles')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Accesos');
    }
};
