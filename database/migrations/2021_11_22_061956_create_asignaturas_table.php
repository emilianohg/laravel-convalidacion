<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsignaturasTable extends Migration
{
    public function up()
    {
        Schema::create('asignaturas', function (Blueprint $table) {
            $table->id('asignatura_id');
            $table->string('plan_estudio_clave');
            $table->string('nombre');
            $table->unsignedInteger('creditos');

            $table->foreign('plan_estudio_clave')
                ->references('clave')
                ->on('planes_estudio');
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignaturas');
    }
}
