<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesHistorialTable extends Migration
{
    public function up()
    {
        Schema::create('solicitudes_historial', function (Blueprint $table) {
            $table->id('solicitudes_historial_id');
            $table->unsignedBigInteger('solicitud_id');
            $table->unsignedBigInteger('usuario_id');
            $table->string('status_id');
            $table->dateTime('fecha');
            $table->text('comentario')->nullable();

            $table->foreign('solicitud_id')
                ->references('solicitud_id')
                ->on('solicitudes');

            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuarios');

            $table->foreign('status_id')
                ->references('status_id')
                ->on('status');

        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitudes_historial');
    }
}
