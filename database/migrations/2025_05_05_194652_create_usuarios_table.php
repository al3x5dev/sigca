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
        Schema::create('Usuarios', function (Blueprint $table) {
            $table->integer('id')->primary()->nullable(false);
            $table->string('nombre',150)->nullable(false);
            $table->string('cargo',150)->nullable(false);
            $table->integer('rol')->nullable(false);
            $table->string('usuario',100)->nullable(false);
            $table->dateTime('creado')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('ultm_acc')->nullable();
            $table->boolean('activo')->default(true);

            $table->foreign('rol')->references('id')->on('Roles')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Usuarios');
    }
};
