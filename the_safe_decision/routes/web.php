<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/server-time', function () {
    return response()->json(['server_time' => now()->toDateTimeString()]);
});