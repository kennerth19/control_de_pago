<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImprimirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imprimir', function (Blueprint $table) {
            $table->string('nombre')->default(0)->nullable();
            $table->string('direccion')->default(0)->nullable();
            $table->string('plan')->default(0)->nullable();
            $table->integer('pendiente')->default(0)->nullable();
            $table->date('fecha_de_corte')->nullable();
            $table->integer('total')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imprimir');
    }
}
