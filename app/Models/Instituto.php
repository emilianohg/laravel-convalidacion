<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instituto extends Model
{
    protected $table = 'institutos';

    protected $primaryKey = 'instituto_id';

    protected $fillable = ['instituto_id', 'nombre', 'direccion'];

    public $timestamps = false;

}
