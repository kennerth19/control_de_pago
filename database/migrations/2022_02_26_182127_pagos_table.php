<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('codigo_de_pago');
            $table->string('full_name')->default(0)->nullable();
            $table->string('id_user')->default(0)->nullable();
            $table->string('dir')->default(0)->nullable();
            $table->integer('type')->default(0)->nullable();
            $table->integer('monto_bs')->default(0)->nullable();
            $table->integer('monto_dollar')->default(0)->nullable();
            $table->integer('monto_zelle_1')->default(0)->nullable();
            $table->integer('monto_zelle_2')->default(0)->nullable();
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
        Schema::dropIfExists('pagos');
    }
}
