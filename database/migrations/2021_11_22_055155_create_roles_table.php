<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->string('rol_id');
            $table->string('nombre');

            $table->primary('rol_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
