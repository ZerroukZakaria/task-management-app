<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Grouping routes under 'v1' prefix for versioning
Route::group(['prefix' => 'v1'], function() {
    
    // Routes that require authentication
    Route::middleware('auth:sanctum')->group(function() {

        //get the authenticated user's details
        Route::get('/user', [AuthController::class, 'getUser']);
        
        //Grouping routes related to tasks under 'tasks' prefix        
        Route::group(['prefix' => 'tasks', 'middleware' => 'auth:sanctum'], function () {
            Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
            Route::get('/{id}', [TaskController::class, 'show'])->name('tasks.show');
            Route::post('/store', [TaskController::class, 'store'])->name('tasks.store');
            Route::put('/{id}', [TaskController::class, 'update'])->name('tasks.update');
            Route::patch('/{id}', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
            Route::delete('/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        });
    }); 

    //login a user     
    Route::post('/login', [AuthController::class, 'login']);

    //register a new user    
    Route::post('/register', [AuthController::class, 'register']);

});


