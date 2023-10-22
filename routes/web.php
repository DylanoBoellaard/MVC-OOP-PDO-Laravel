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

Route::get('/instructeur/gebruikteVoertuigen/{instructeur}', [InstructeurController::class, 'gebruikteVoertuigen'])->name('instructeur.gebruikteVoertuigen'); // already used & assigned vehicles list page

Route::get('/instructeur/beschikbareVoertuigen/{instructeur}', [InstructeurController::class, 'beschikbareVoertuigen'])->name('instructeur.beschikbareVoertuigen'); // available, unassigned voertuigen list page

Route::get('/instruceur/addVehicle.{instructeur}/{voertuig}', [InstructeurController::class, 'addVehicle'])->name('instructeur.addVehicle'); // Add unassigned vehicle to instructor page

Route::get('/instructeur/wijzigenVoertuigen/{instructeur}/{voertuigGegevens}', [InstructeurController::class, 'wijzigenVoertuigen'])->name('instructeur.wijzigenVoertuigen'); // edit vehicle data list page

Route::put('/instructeur/update/{instructeur}/{voertuig}', [InstructeurController::class, 'update'])->name('instructeur.update'); // Update vehicle data page