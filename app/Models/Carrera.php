<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Carrera extends Model
{
    protected $table = 'carreras';

    protected $primaryKey = 'carrera_id';

    protected $fillable = ['carrera_id', 'nombre', 'instituto_id'];

    public $timestamps = false;

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'coordinadores',
            'carrera_id',
            'usuario_id',
        );
    }

}
