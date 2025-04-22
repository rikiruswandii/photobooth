<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('Index');
});

Route::post('/process', function (Request $request) {
    $images = $request->input('images'); // ambil data gambar dari request
    return Inertia::render('Process', [
        'images' => $images,
    ]);
});

Route::get('/process', function () {
    $images = session('images', []); // ambil dari session
    return Inertia::render('Process', [
        'images' => $images,
    ]);
}); 