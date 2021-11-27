<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrientacionVocacionalTable extends Migration
{
    public function up()
    {
        Schema::create('orientacion_vocacional', function (Blueprint $table) {
            $table->id('orientacion_vocacional_id');
            $table->unsignedBigInteger('solicitud_id');
            $table->unsignedBigInteger('evaluador_id');
            $table->dateTime('fecha_aplicacion');
            $table->dateTime('fecha_revision')->nullable();
            $table->text('comentario')->nullable();
            $table->boolean('es_recomendado')->nullable();

            $table->foreign('solicitud_id')
                ->references('solicitud_id')
                ->on('solicitudes');

            $table->foreign('evaluador_id')
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orientacion_vocacional');
    }
}
