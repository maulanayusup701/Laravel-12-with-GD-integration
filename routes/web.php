<?php

use App\Http\Controllers\GDIntegrationController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', [GDIntegrationController::class, 'index']);
