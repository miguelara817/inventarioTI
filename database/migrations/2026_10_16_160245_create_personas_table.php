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
        Schema::create('personas', function (Blueprint $table) {
            $table->id('persona_id');
            $table->string('nombre', 50)->nullable();
            $table->string('apellido', 50)->nullable();
            $table->foreignId('unidad_id')->nullable()->constrained('unidades', 'unidad_id')->deferrable();
            $table->boolean('is_active')->default(true)->notNull();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['nombre', 'apellido']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
