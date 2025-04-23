<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/gallery', function () {
    return view('gallery');
})->name('gallery');

Route::get('/games', function () {
    return view('games');
})->name('games');

Route::get('/messages', function () {
    return view('messages');
})->name('messages');
