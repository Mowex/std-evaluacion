<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Movie;
use App\Models\Turn;

class dashboardController extends Controller
{
    public function index() {
        $movieCount = Movie::count();
        $turnCount = Turn::count();
        $turnsAssigned = Turn::whereNotNull('movie_id')->count();

        return response()->json([
            'success' => true,
            'movieCount' => $movieCount,
            'turnCount' => $turnCount,
            'turnsAssigned' => $turnsAssigned,
        ]);
    }
}
