<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsignaturasCursadasTable extends Migration
{
    public function up()
    {
        Schema::create('asignaturas_cursadas', function (Blueprint $table) {
            $table->string('numero_control');
            $table->unsignedBigInteger('asignatura_id');

            $table->primary(['numero_control', 'asignatura_id']);

            $table->foreign('numero_control')
                ->references('numero_control')
                ->on('alumnos');

            $table->foreign('asignatura_id')
                ->references('asignatura_id')
                ->on('asignaturas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignaturas_cursadas');
    }
}
