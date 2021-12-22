<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudHistorial extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_historial';

    protected $primaryKey = 'solicitud_historial_id';

    public $incrementing = true;

    protected $fillable = [
        'solicitud_id',
        'usuario_id',
        'status_id',
        'fecha',
        'comentario',
    ];

    public $timestamps = false;
}
