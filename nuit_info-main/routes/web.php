<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
Route::get('/index-2', function () {
    return view('index-2');
});
Route::get('/simulateur', function () {
    return view('simulateur');
});
Route::get('/demarches', function () {
    return view('demarche');
});
Route::get('/pilote', function () {
    return view('pilote');
});
Route::get('/linux-nird', function () {
    return view('linux-nird');
});
Route::get('/faire-un-don', function () {
    return view('faire-un-don');
});