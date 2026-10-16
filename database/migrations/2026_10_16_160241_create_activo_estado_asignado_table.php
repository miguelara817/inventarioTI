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
        Schema::create('activo_estado_asignado', function (Blueprint $table) {
            $table->increments('activo_estado_asignado_id');
            $table->string('valor', 50)->nullable();
            $table->timestamp('created_at')->default('2026-10-15 15:52:50.907893');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activo_estado_asignado');
    }
};
