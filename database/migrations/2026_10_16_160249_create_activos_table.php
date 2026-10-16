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
            $table->increments('activo_id');
            $table->string('codigo_activo', 100)->notNull()->unique();
            $table->foreignId('activo_tipo_id')->constrained('activo_tipos', 'activo_tipos_id')->nullable()->deferrable();
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 150)->nullable();
            $table->string('numero_serie', 100)->nullable();
            $table->foreignId('ubicacion_id')->constrained('ubicaciones', 'ubicacion_id')->nullable()->deferrable();
            $table->foreignId('estado_asignado')->constrained('activo_estado_asignado', 'activo_estado_asignado_id')->nullable()->deferrable();
            $table->foreignId('estado_operativo')->constrained('activo_estado_operativo', 'activo_estado_operativo_id')->nullable()->deferrable();
            $table->foreignId('estado_fisico')->constrained('activo_estado_fisico', 'activo_estado_fisico_id')->nullable()->deferrable();
            $table->foreignId('persona_asignada')->constrained('personas', 'persona_id')->nullable()->deferrable();
            $table->timestamp('fecha_registro')->default('2026-10-15 15:52:50.89063');
            $table->text('observaciones')->nullable();
            $table->timestamp('created_at')->default('2026-10-15 15:52:50.89063');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
