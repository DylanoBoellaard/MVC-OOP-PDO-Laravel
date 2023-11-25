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

// Default homepage
Route::get('/', function () {
    return view('welcome');
});

// Instructeur namen lijst
Route::get('/index', [InstructeurController::class, 'index'])->name('instructeur.index');

// already used & assigned vehicles list page
Route::get('/instructeur/gebruikteVoertuigen/{instructeur}', [InstructeurController::class, 'gebruikteVoertuigen'])->name('instructeur.gebruikteVoertuigen');

// available, unassigned voertuigen list page
Route::get('/instructeur/beschikbareVoertuigen/{instructeur}', [InstructeurController::class, 'beschikbareVoertuigen'])->name('instructeur.beschikbareVoertuigen');

// Page to actually allow adding selected unassigned vehicle to selected instructor
Route::get('/instruceur/addVehicle.{instructeur}/{voertuig}', [InstructeurController::class, 'addVehicle'])->name('instructeur.addVehicle');

// Page to edit vehicle details
Route::get('/instructeur/wijzigenVoertuigen/{instructeur}/{voertuigGegevens}', [InstructeurController::class, 'wijzigenVoertuigen'])->name('instructeur.wijzigenVoertuigen');

// Page to actually allow updating the selected vehicle details
Route::put('/instructeur/update/{instructeur}/{voertuig}', [InstructeurController::class, 'update'])->name('instructeur.update');

// Page to actually allow unassigning the selected vehicle
Route::get('/instructeur/unassign/{instructeur}/{voertuig}', [InstructeurController::class, 'unassign'])->name('instructeur.unassign');

// Page to display all vehicles, assigned and unassigned
Route::get('/instructeur/alleVoertuigen/', [InstructeurController::class, 'alleVoertuigen'])->name('instructeur.alleVoertuigen');

// Page to actually allow deleting the selected vehicle
Route::get('/instructeur/delete/{voertuig}', [InstructeurController::class, 'delete'])->name('instructeur.delete');

// Page for unassigning instructor
Route::get('/instructeur/notActive/{instructeur}', [InstructeurController::class, 'notActive'])->name('instructeur.notActive');

// Page for reassigning instructor
Route::get('/instructeur/active/{instructeur}', [InstructeurController::class, 'active'])->name('instructeur.active');
