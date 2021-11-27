<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoordinadoresTable extends Migration
{
    public function up()
    {
        Schema::create('coordinadores', function (Blueprint $table) {
            $table->unsignedBigInteger('usuario_id');
            $table->string('carrera_id');

            $table->primary(['usuario_id', 'carrera_id']);

            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuarios');

            $table->foreign('carrera_id')
                ->references('carrera_id')
                ->on('carreras');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coordinadores');
    }
}
