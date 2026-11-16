<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');


Route::get('/login', function () {
    return 'Halaman Login';
})->name('login');


Route::get('/logbook-siswa', function () {
    return 'Halaman Logbook Siswa';
})->name('logbook.siswa');
