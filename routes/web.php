<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstructeurController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/index', [InstructeurController::class, 'index'])->name('instructeur.index'); // Instructeur list

Route::get('/gebruikteVoertuigen', [InstructeurController::class, 'gebruikteVoertuigen'])->name('instructeur.gebruikteVoertuigen'); // Gebruikte voertuigen list