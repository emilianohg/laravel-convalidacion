<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Adeudo extends Model
{
    protected $table = 'adeudos';

    protected $primaryKey = 'adeudo_id';

    public $incrementing = true;

    protected $fillable = ['adeudo_id', 'numero_control', 'descripcion', 'importe'];

    public $timestamps = false;

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(
            Alumno::class,
            'numero_control',
            'numero_control',
        );
    }

}
