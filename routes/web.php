<?php

use App\Http\Controllers\IntegrationController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::controller(IntegrationController::class)->group(function () {
    Route::get('/upload', 'googleDrive');
    Route::get('/event', 'googleCalendar');
});
