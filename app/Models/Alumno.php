<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Alumno extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'alumnos';

    protected $primaryKey = 'numero_control';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'plan_estudio_id',
        'semestre',
    ];

}
