<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanesEstudioTable extends Migration
{
    public function up()
    {
        Schema::create('planes_estudio', function (Blueprint $table) {
            $table->string('clave')->primary();
            $table->string('carrera_id');
            $table->unsignedInteger('total_creditos');
            $table->boolean('es_vigente')->default(true);

            $table->foreign('carrera_id')
                ->references('carrera_id')
                ->on('carreras');
        });
    }

    public function down()
    {
        Schema::dropIfExists('planes_estudio');
    }
}
