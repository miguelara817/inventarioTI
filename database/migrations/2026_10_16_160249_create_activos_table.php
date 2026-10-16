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
        Schema::create('activos', function (Blueprint $table) {
            $table->id('activo_id');
            $table->string('codigo_activo', 100)->notNull()->unique();
            $table->foreignId('activo_tipo_id')->nullable()->constrained('activo_tipos', 'activo_tipos_id')->deferrable();
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 150)->nullable();
            $table->string('numero_serie', 100)->nullable();
            $table->foreignId('ubicacion_id')->nullable()->constrained('ubicaciones', 'ubicacion_id')->deferrable();
            $table->foreignId('estado_asignado')->nullable()->constrained('activo_estado_asignado', 'activo_estado_asignado_id')->deferrable();
            $table->foreignId('estado_operativo')->nullable()->constrained('activo_estado_operativo', 'activo_estado_operativo_id')->deferrable();
            $table->foreignId('estado_fisico')->nullable()->constrained('activo_estado_fisico', 'activo_estado_fisico_id')->deferrable();
            $table->foreignId('persona_asignada')->nullable()->constrained('personas', 'persona_id')->deferrable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activos');
    }
};
