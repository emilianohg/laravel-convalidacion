<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesTable extends Migration
{
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id('solicitud_id');
            $table->string('carrera_id');
            $table->string('numero_control');
            $table->string('status_id');

            $table->foreign('numero_control')
                ->references('numero_control')
                ->on('alumnos');

            $table->foreign('carrera_id')
                ->references('carrera_id')
                ->on('carreras');

            $table->foreign('status_id')
                ->references('status_id')
                ->on('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitudes');
    }
}
