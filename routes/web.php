<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CasillaController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\EleccionController;
use App\Http\Controllers\VotoController;
use App\Http\Controllers\PDFController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('casilla', CasillaController::class);
Route::resource('candidato', CandidatoController::class);
Route::resource('eleccion', EleccionController::class);
Route::resource('voto', VotoController::class);
#--- Socialite facebook
Route::get('/login','Auth\LoginController@index');
Route::get('/login/facebook', 'Auth\LoginController@redirectToFacebookProvider');
Route::get('/login/facebook/callback', 'Auth\LoginController@handleProviderFacebookCallback');