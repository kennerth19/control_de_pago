<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_de_plan')->default(0)->nullable();
            $table->string('plan')->default(0)->nullable();
            $table->string('dedicado')->default(0)->nullable();
            $table->integer('valor')->default(0)->nullable();
            $table->string('descripcion')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('planes');
    }
}
