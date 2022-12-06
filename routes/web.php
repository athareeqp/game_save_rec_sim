<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ObjekController;
use App\Http\Controllers\JoinController;

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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
  
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::get('players/trash', [PlayerController::class, 'deletelist']);
    Route::get('players/trash/{player}/restore', [PlayerController::class, 'restore']);
    Route::get('players/trash/{player}/forcedelete', [PlayerController::class, 'deleteforce']);
    Route::resource('players', PlayerController::class);
    Route::get('games/trash', [GameController::class, 'deletelist']);
    Route::get('games/trash/{game}/restore', [GameController::class, 'restore']);
    Route::get('games/trash/{game}/forcedelete', [GameController::class, 'deleteforce']);
    Route::resource('games', GameController::class);
    Route::get('maps/trash', [MapController::class, 'deletelist']);
    Route::get('maps/trash/{map}/restore', [MapController::class, 'restore']);
    Route::get('maps/trash/{map}/forcedelete', [MapController::class, 'deleteforce']);
    Route::resource('maps', MapController::class);
    Route::get('objeks/trash', [ObjekController::class, 'deletelist']);
    Route::get('objeks/trash/{objek}/restore', [ObjekController::class, 'restore']);
    Route::get('objeks/trash/{objek}/forcedelete', [ObjekController::class, 'deleteforce']);
    Route::resource('objeks', ObjekController::class);
    Route::get('/totals', [JoinController::class,'index']);
});