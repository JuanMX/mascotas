<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PetTypeController;
use App\Http\Controllers\AdopterTypeController;

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
Route::get('/home', function () {
    return view('welcome');
});

Route::prefix('catalogue')->group(function () {
    Route::get('/pettype', [PetController::class, 'index']);
    Route::post('/list-pet-type', [PetTypeController::class, 'listPetType']);

    Route::get('/adoptertype', [AdopterTypeController::class, 'index']);
    Route::post('/list-adopter-type', [AdopterTypeController::class, 'listAdopterType']);
});
