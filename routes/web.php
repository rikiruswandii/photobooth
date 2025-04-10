<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/photobooth', function () {
    return Inertia::render('Index');
});

Route::get('/', function () {
    return view('welcome');
});