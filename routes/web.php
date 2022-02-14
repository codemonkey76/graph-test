<?php

use App\Http\Controllers\GraphController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GraphController::class, 'show']);
