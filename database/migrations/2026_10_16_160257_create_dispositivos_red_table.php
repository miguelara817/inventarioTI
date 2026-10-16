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
            $table->id('dispositivos_red_id');
            $table->foreignId('activo_id')->nullable()->unique()->constrained('activos', 'activo_id')->deferrable();
            $table->string('hostname', 150)->nullable();
            $table->string('direccion_ip', 100)->nullable();
            $table->string('direccion_mac', 100)->nullable();
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
        Schema::dropIfExists('dispositivos_red');
    }
};
