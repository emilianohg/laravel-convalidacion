<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalificacionToAsignaturasCursadasTable extends Migration
{
    public function up()
    {
        Schema::table('asignaturas_cursadas', function (Blueprint $table) {
            $table->unsignedInteger('calificacion');
        });
    }

    public function down()
    {
        Schema::table('asignaturas_cursadas', function (Blueprint $table) {
            $table->dropColumn('calificacion');
        });
    }
}
