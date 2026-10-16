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
        Schema::create('dispositivos_red', function (Blueprint $table) {
            $table->increments('dispositivos_red_id');
            $table->foreignId('activo_id')->constrained('activos', 'activo_id')->nullable()->deferrable()->unique();
            $table->string('hostname', 150)->nullable();
            $table->string('direccion_ip', 100)->nullable();
            $table->string('direccion_mac', 100)->nullable();
            $table->timestamp('created_at')->default('2026-10-15 15:52:50.934578');
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
        Schema::dropIfExists('dispositivos_red');
    }
};
