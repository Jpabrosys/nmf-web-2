<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExportController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/trigger-export', [ExportController::class, 'trigger'])
    ->middleware('auth.export')
    ->name('api.export.trigger');

Route::get('/lazy-load-section/{section_id}', 
    [App\Http\Controllers\HomeController::class, 'lazyLoadSection']
)->name('api.lazy-load-section');

//YesAway Api Routes
Route::controller(App\Http\Controllers\Api\YesAwayApiController::class)
    ->prefix('yesaway/v1')
    ->group(function () {
        Route::get('/locations','getAllLocations')->name('getYesAwayAllLocations');
        Route::get('/locations/download','getAllLocationsDownload')->name('getYesAwayAllLocationsDownload');
        Route::get('/location/{location_code}','getLocation')->name('getYesAwayLocation');
        Route::get('/search/vehicles/test','searchVehiclesForLocationTest')->name('searchYesAwayVehiclesForLocationTest');
        Route::post('/search/vehicles','searchVehiclesForLocation')->name('searchYesAwayVehiclesForLocation');
    });

//Nissa Api Routes
Route::controller(App\Http\Controllers\Api\NissaApiController::class)
    ->prefix('nissa/v1')
    ->group(function () {
        Route::get('/locations','getAllLocations')->name('getNissaAllLocations');
        Route::get('/locations/download','getAllLocationsDownload')->name('getNissaAllLocationsDownload');
        Route::get('/search/vehicles/test','searchVehiclesForLocationTest')->name('searchNissaVehiclesForLocationTest');
        Route::post('/search/vehicles','searchVehiclesForLocation')->name('searchNissaVehiclesForLocation');
    });    
    

//Easygo Api Routes
Route::controller(App\Http\Controllers\Api\EasygoApiController::class)
    ->prefix('easygo/v1')
    ->group(function () {
        Route::get('/locations','getAllLocations')->name('getEasygoAllLocations');
        Route::get('/locations/download','getAllLocationsDownload')->name('getEasygoAllLocationsDownload');
        Route::get('/search/vehicles/test','searchVehiclesForLocationTest')->name('searchEasygoVehiclesForLocationTest');
        Route::post('/search/vehicles','searchVehiclesForLocation')->name('searchEasygoVehiclesForLocation');
    });        

//Easirent Api Routes
Route::controller(App\Http\Controllers\Api\EasirentApiController::class)
    ->prefix('easirent/v1')
    ->group(function () {
        Route::get('/locations','getAllLocations')->name('getEasirentAllLocations');
        Route::get('/locations/download','getAllLocationsDownload')->name('getEasirentAllLocationsDownload');
        Route::get('/search/vehicles/test','searchVehiclesForLocationTest')->name('searchEasirentVehiclesForLocationTest');
        Route::post('/search/vehicles','searchVehiclesForLocation')->name('searchEasirentVehiclesForLocation');
        Route::get('/book/vehicle/test','bookVehicleForLocationTest')->name('bookVehicleForLocationTest');
    });    
    
//Public Api
Route::prefix('')
    ->group(function () {
    Route::post('/get_cars',[App\Http\Controllers\FilterController::class, 'callApi'])->name('callApi');
    Route::get('/all_locations', [App\Http\Controllers\LocationController::class, 'getAllLocations'])->name('getAllLocations');
});
