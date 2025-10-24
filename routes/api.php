<?php

use App\Http\Controllers\Api\{EventController, AttendeeController};
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::apiResource('events', EventController::class);
Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped()->except('update');

Route::resource('auth', AuthController::class)
    ->only('store', 'destroy');