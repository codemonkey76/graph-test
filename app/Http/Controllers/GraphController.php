<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Services\FusionApiService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    public function show()
    {
        return view('welcome');
    }
}
