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
            $table->id('computadora_id');
            $table->foreignId('activo_id')->nullable()->unique()->constrained('activos', 'activo_id')->deferrable();
            $table->string('hostname', 150)->nullable();
            $table->string('procesador', 50)->nullable();
            $table->string('memoria_ram', 50)->nullable();
            $table->string('almacenamiento', 50)->nullable();
            $table->string('video', 50)->nullable();
            $table->string('direccion_ip', 100)->nullable();
            $table->string('direccion_mac', 100)->nullable();
            $table->string('sistema_operativo', 100)->nullable();
            $table->string('version_so', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

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
