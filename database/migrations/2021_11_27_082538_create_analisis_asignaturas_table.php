<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalisisAsignaturasTable extends Migration
{
    public function up()
    {
        Schema::create('analisis_asignaturas', function (Blueprint $table) {
            $table->id('analisis_asignatura_id');
            $table->unsignedBigInteger('analisis_academico_id');
            $table->unsignedBigInteger('asignatura_cursada_id');
            $table->unsignedBigInteger('asignatura_convalidar_id')->nullable();
            $table->unsignedInteger('calificacion');
            $table->decimal('porcentaje')->nullable();

            $table->foreign('asignatura_cursada_id')
                ->references('asignatura_id')
                ->on('asignaturas');

            $table->foreign('asignatura_convalidar_id')
                ->references('asignatura_id')
                ->on('asignaturas');

            $table->foreign('analisis_academico_id')
                ->references('analisis_academico_id')
                ->on('analisis_academico');
        });
    }

    public function down()
    {
        Schema::dropIfExists('analisis_asignaturas');
    }
}
