<?php


namespace App\Utils;


use App\Models\User;
use Illuminate\Support\Facades\DB;

class ValidarUtils
{

    public static function validarCoordinacion(User $user, string $carreraId): bool
    {
        if ($user->rol_id != 'coordinador') {
            return false;
        }

        $carrerasId = DB::table('coordinadores')
            ->where('coordinadores.usuario_id', '=', $user->id)
            ->get()
            ->map(fn ($coordinador) => $coordinador->carrera_id)
            ->toArray();

        $existe = collect($carrerasId)
            ->filter(fn ($_carreraId) => $carreraId == $_carreraId)
            ->count();

        if ($existe == 0) {
            return false;
        }

        return true;
    }

}