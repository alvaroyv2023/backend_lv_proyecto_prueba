<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->dateTime("fecha");
            $table->integer("estado")->default(1); // 1: pendiente, 2: completado, 3: cancelado
            $table->bigInteger("user_id")->unsigned();
            $table->bigInteger("cliente_id")->unsigned();

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("cliente_id")->references("id")->on("clientes");

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
        Schema::dropIfExists('pedidos');
    }
};
