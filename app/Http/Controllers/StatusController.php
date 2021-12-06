<?php

namespace App\Http\Controllers;

use App\Models\Status;

class StatusController extends Controller
{
    public function __invoke()
    {
        $status = Status::query()
            ->select([
                'status_id',
                'nombre as status'
            ])
            ->get();
        return response()->json($status);
    }
}
