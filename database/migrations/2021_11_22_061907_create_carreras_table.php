<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrerasTable extends Migration
{
    public function up()
    {
        Schema::create('carreras', function (Blueprint $table) {
            $table->string('carrera_id')->primary();
            $table->unsignedBigInteger('instituto_id');
            $table->string('nombre');


            $table->foreign('instituto_id')
                ->references('instituto_id')
                ->on('institutos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carreras');
    }
}
