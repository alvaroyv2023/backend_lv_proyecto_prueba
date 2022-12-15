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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 200);
            $table->decimal("precio", 10, 2)->default(0);
            $table->integer("stock")->default(0);
            $table->string("imagen")->nullable();
            $table->text("descripcion")->nullable();
            $table->boolean("estado")->default(true);
            // N:1
            $table->bigInteger("categoria_id")->unsigned();
            $table->foreign("categoria_id")->references("id")->on("categorias");
            
            $table->softDeletes(); // deleted_at
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
        Schema::dropIfExists('productos');
    }
};
