<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotspotsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add-hotspot', [HotspotsController::class, 'newHotspotForm']);
Route::post('/add-hotspot', [HotspotsController::class, 'addNewHotspot']);

Route::get('/activity', [HotspotsController::class, 'getLastActivity']);
Route::get('/test', [HotspotsController::class, 'testSms']);

