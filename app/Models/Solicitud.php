<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $primaryKey = 'solicitud_id';

    public $incrementing = true;

    protected $fillable = [
        'solicitud_id',
        'carrera_id',
        'numero_control',
        'status_id',
    ];

    public $timestamps = false;
}
