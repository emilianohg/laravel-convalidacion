<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';

    protected $primaryKey = 'status_id';

    public $incrementing = false;

    protected $fillable = ['status_id', 'nombre'];

    public $timestamps = false;

    public function solicitudes(): HasMany
    {
        return $this->hasMany(User::class, 'rol_id', 'rol_id');
    }

}
