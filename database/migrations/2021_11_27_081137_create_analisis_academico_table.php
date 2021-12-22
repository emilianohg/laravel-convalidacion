<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalisisAcademicoTable extends Migration
{
    public function up()
    {
        Schema::create('analisis_academico', function (Blueprint $table) {
            $table->id('analisis_academico_id');
            $table->unsignedBigInteger('solicitud_id');
            $table->unsignedBigInteger('evaluado_por')->nullable();
            $table->unsignedBigInteger('autorizado_por')->nullable();

            $table->foreign('solicitud_id')
                ->references('solicitud_id')
                ->on('solicitudes');

            $table->foreign('evaluado_por')
                ->references('id')
                ->on('usuarios');

            $table->foreign('autorizado_por')
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('analisis_academico');
    }
}
