<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    protected $table = 'asignaturas';

    protected $primaryKey = 'asignatura_id';

    protected $fillable = ['asignatura_id', 'plan_estudio_clave', 'nombre', 'creditos'];

    public $timestamps = false;

}
