<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutosTable extends Migration
{
    public function up()
    {
        Schema::create('institutos', function (Blueprint $table) {
            $table->id('instituto_id');
            $table->string('nombre');
            $table->text('direccion');
        });
    }

    public function down()
    {
        Schema::dropIfExists('institutos');
    }
}
