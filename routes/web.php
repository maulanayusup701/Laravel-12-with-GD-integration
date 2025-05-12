<?php

use App\Http\Controllers\IntegrationController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', [IntegrationController::class, 'googleDrive']);
Route::get('/event', [IntegrationController::class, 'googleCalendar']);
