<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $primaryKey = 'rol_id';

    public $incrementing = false;

    protected $fillable = ['rol_id', 'nombre'];

    public $timestamps = false;

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'rol_id', 'rol_id');
    }

}
