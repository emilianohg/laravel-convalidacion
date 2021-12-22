<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanEstudio extends Model
{
    protected $table = 'planes_estudio';

    protected $primaryKey = 'clave';

    public $incrementing = false;

    protected $fillable = ['clave', 'carrera_id', 'total_creditos', 'es_vigente'];

    public $timestamps = false;

}
