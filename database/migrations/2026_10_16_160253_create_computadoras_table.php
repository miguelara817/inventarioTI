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
        Schema::create('computadoras', function (Blueprint $table) {
            $table->increments('computadora_id');
            $table->foreignId('activo_id')->constrained('activos', 'activo_id')->nullable()->deferrable()->unique();
            $table->string('hostname', 150)->nullable();
            $table->string('procesador', 50)->nullable();
            $table->string('memoria_ram', 50)->nullable();
            $table->string('almacenamiento', 50)->nullable();
            $table->string('video', 50)->nullable();
            $table->string('direccion_ip', 100)->nullable();
            $table->string('direccion_mac', 100)->nullable();
            $table->string('sistema_operativo', 100)->nullable();
            $table->string('version_so', 100)->nullable();
            $table->timestamp('created_at')->default('2026-10-15 15:52:50.925642');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->index('direccion_ip');
            $table->index('direccion_mac');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computadoras');
    }
};
