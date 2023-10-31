<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdopterController;
use App\Http\Controllers\AdoptionController;
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



Route::prefix('pet')->group(function () {
    Route::get('/pet', [PetController::class, 'index']);
    Route::post('/list-pets-with-status', [PetController::class, 'listPetsWithStatus']);
});

Route::prefix('adoption')->group(function () {

    Route::post('/list-all-pets', [PetController::class, 'listAllPets']);
    Route::post('/list-all-adopters', [AdopterController::class, 'listAllAdopters']);
    

    Route::get('/adopt', [AdoptionController::class, 'indexAdoption']);
    Route::get('/return', [AdoptionController::class, 'indexRefund']);
    Route::post('/adopter-info-for-pet', [AdoptionController::class, 'adopterInfoForPet']);

    Route::get('/deliberate', [AdoptionController::class, 'indexDeliberate']);
    Route::post('/list-adopt-requests', [AdoptionController::class, 'listAdoptRequests']);
    Route::post('/list-return-requests', [AdoptionController::class, 'listReturnRequests']);

     ////////////////////
    //Timeline section//
   ////////////////////
    Route::get('/timeline', [AdoptionController::class, 'indexTimeline']);
    Route::post('/timeline-pet', [AdoptionController::class, 'timelinePet']);
    Route::post('/timeline-adopter', [AdoptionController::class, 'timelineAdopter']);

});

Route::prefix('catalogue')->group(function () {
    Route::get('/pettype', [PetTypeController::class, 'index']);
    Route::post('/list-pet-type', [PetTypeController::class, 'listPetType']);
    Route::post('/create-pettype', [PetTypeController::class, 'createType']);
    Route::patch('/edit-pettype', [PetTypeController::class, 'editType']);
    Route::delete('/delete-pettype', [PetTypeController::class, 'deleteType']);

    Route::get('/adoptertype', [AdopterTypeController::class, 'index']);
    Route::post('/list-adopter-type', [AdopterTypeController::class, 'listAdopterType']);
    Route::post('/create-adoptertype', [AdopterTypeController::class, 'createType']);
    Route::patch('/edit-adoptertype', [AdopterTypeController::class, 'editType']);
    Route::delete('/delete-adoptertype', [AdopterTypeController::class, 'deleteType']);
});
