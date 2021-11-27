<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnosTable extends Migration
{
    public function up()
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->string('numero_control')->primary();
            $table->unsignedBigInteger('usuario_id');
            $table->string('plan_estudio_id');
            $table->integer('semestre');

            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuarios');

            $table->foreign('plan_estudio_id')
                ->references('clave')
                ->on('planes_estudio');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumnos');
    }
}
