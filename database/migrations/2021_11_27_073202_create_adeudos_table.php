<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdeudosTable extends Migration
{
    public function up()
    {
        Schema::create('adeudos', function (Blueprint $table) {
            $table->id('adeudo_id');
            $table->string('numero_control');
            $table->string('descripcion');
            $table->decimal('importe');

            $table->foreign('numero_control')
                ->references('numero_control')
                ->on('alumnos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('adeudos');
    }
}
