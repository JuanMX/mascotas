<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdopterController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\SimpleCatalogueController;
use App\Http\Controllers\DashboardController;

use App\Mail\AdoptionDeliberation;

use App\Http\Controllers\TestController;
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

Route::get('/', [DashboardController::class, 'index']);
Route::get('/home', [DashboardController::class, 'index']);
Route::post('/dashboard-total', [DashboardController::class, 'dashboardTotal']);
Route::post('/dashboard-pets-pending', [DashboardController::class, 'dashboardPetsPending']);
Route::post('/dashboard-pets-requests', [DashboardController::class, 'dashboardPetsRequests']);
Route::post('/dashboard-bar-chart', [DashboardController::class, 'dashboardBarChart']);
Route::post('/dashboard-latest-adoptions-actions', [DashboardController::class, 'dashboardLatestAdoptionsActions']);

Route::prefix('pet')->group(function () {
    Route::get('/pet', [PetController::class, 'index']);
    Route::post('/list-pets-with-status', [PetController::class, 'listPetsWithStatus']);
    Route::post('/create', [PetController::class, 'create']);
    Route::patch('/edit', [PetController::class, 'edit']);
    Route::delete('/delete', [PetController::class, 'delete']);

    Route::get('/return', [PetController::class, 'indexPetReturnToTheShelter']);
    Route::post('/list-pet-adopter-adoption-with-statuses', [PetController::class, 'listPetAdopterAdoptionWithStatuses']);
    Route::post('/list-pet-and-its-adopter', [AdoptionController::class, 'listPetAndItsAdopter']);
    Route::post('/pet-returned-to-the-shelter', [PetController::class, 'petReturnedToTheShelter']);

    Route::get('/pickedup', [PetController::class, 'indexPetPickedUp']);
    Route::post('/pet-picked-up', [PetController::class, 'petPickedUp']);
});

Route::prefix('adoption')->group(function () {

    Route::post('/list-all-pets', [PetController::class, 'listAllPets']);
    Route::post('/list-all-adopters', [AdopterController::class, 'listAllAdopters']);
    

    Route::get('/adopt', [AdoptionController::class, 'indexAdoption']);
    Route::post('/adoption-request', [AdoptionController::class, 'adoptionRequest']);

    Route::get('/return', [AdoptionController::class, 'indexReturn']);
    Route::post('/return-request', [AdoptionController::class, 'returnRequest']);

    Route::get('/deliberate-adoption', [AdoptionController::class, 'indexDeliberate']);
    Route::post('/list-adopt-requests', [AdoptionController::class, 'listAdoptRequests']);
    Route::post('/adoption-deliberated', [AdoptionController::class, 'adoptionDeliberated']);

    Route::get('/deliberate-return', [AdoptionController::class, 'indexDeliberateReturn']);
    Route::post('/list-return-requests', [AdoptionController::class, 'listReturnRequests']);
    Route::post('/return-deliberated', [AdoptionController::class, 'returnDeliberated']);

     ////////////////////
    //Timeline section//
   ////////////////////
    Route::get('/timeline', [AdoptionController::class, 'indexTimeline']);
    Route::post('/timeline-pet', [AdoptionController::class, 'timelinePet']);
    Route::post('/timeline-adopter', [AdoptionController::class, 'timelineAdopter']);

    Route::get('/test', [TestController::class, 'testDatatableDetailsControlAllOpen']);
});

Route::prefix('catalogue')->group(function () {
    Route::get('/{catalogue}', [SimpleCatalogueController::class, 'index']);
    Route::post('/create', [SimpleCatalogueController::class, 'create']);
    Route::post('/read', [SimpleCatalogueController::class, 'read']);
    Route::patch('/update', [SimpleCatalogueController::class, 'update']);
    Route::delete('/delete', [SimpleCatalogueController::class, 'delete']);
});
