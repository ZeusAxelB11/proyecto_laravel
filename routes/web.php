<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CasillaController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\EleccionController;
use App\Http\Controllers\VotoController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\Auth\LoginController;
use PHPUnit\TextUI\XmlConfiguration\Group;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('casilla', CasillaController::class);
Route::resource('candidato', CandidatoController::class);
Route::resource('eleccion', EleccionController::class);
Route::resource('voto', VotoController::class);

#--- Socialite facebook
/*Route::get('/login',[LoginController::class,'index']) -> name ("login");
Route::get('/login/facebook', [LoginController::class, 'redirectToFacebookProvider']);
Route::get('/login/facebook/callback', [LoginController::class, 'handleProviderFacebookCallback']);
Route::middleware(['auth']) -> group (function() {
    Route::resource('candidato', VotoController::class);
});
*/

#--- Control de PDF
Route::get('generate-pdf', [PDFController::class, 'generatePDF']);
Route::get('preview', 'App\Http\Controllers\PDFController@preview');
Route::get('download', 'App\Http\Controllers\PDFController@download')->name('download');
