<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $value = "Default";
            $table->increments('id');
            $table->integer('status')->default(1);  
            $table->integer("active")->default(1);
            $table->string('full_name')->nullable();
            $table->string('id_user')->nullable();
            $table->string('dir')->nullable();
            $table->string('tlf')->nullable();
            $table->date('day')->nullable();
            $table->date('cut')->nullable();
            $table->string('plan')->default('3mb');
            $table->integer('total')->default(30);
            $table->integer('advan')->nullable();
            $table->string('zone')->default($value);
            $table->string('type')->default($value);
            $table->string('ip')->default($value);
            $table->string('mac')->default($value);
            $table->string('reciever')->default($value)->nullable();
            $table->string('observation')->default("Sin observaciones")->nullable();
            $table->string('pic')->default($value)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('clientes');
    }
}
