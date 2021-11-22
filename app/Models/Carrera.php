<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = 'carreras';

    protected $primaryKey = 'carrera_id';

    protected $fillable = ['carrera_id', 'nombre', 'instituto_id'];

    public $timestamps = false;

}
