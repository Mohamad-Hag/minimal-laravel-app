<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middlewares\AuthMiddleware;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimesheetController;

// /api
Route::group(['middleware'=>'api'], function () {
  
  Route::group(['prefix'=>'auth'], function () {
    Route::post('/register', [AuthController::class,'register']);  
    Route::post('/login', [AuthController::class,'login']);
    Route::post('/logout', [AuthController::class, 'logout']);  
  });

  Route::middleware([AuthMiddleware::class])->group(function () {
    Route::group(['prefix'=>'users'], function () {
      Route::get('/', [UserController::class, 'getAll']);
      Route::get('/{id}', [UserController::class,'get']);      
      Route::put('/update/{id}', [UserController::class, 'update']);
      Route::delete('/delete/{id}', [UserController::class, 'delete']);
    });
    Route::group(['prefix'=>'projects'], function () {
      Route::get('/', [ProjectController::class, 'getAll']);
      Route::get('/{id}', [ProjectController::class,'get']);
      Route::post('/create', [ProjectController::class,'create']);
      Route::put('/update/{id}', [ProjectController::class, 'update']);
      Route::delete('/delete/{id}', [ProjectController::class, 'delete']);
    });

    Route::group(['prefix'=>'timesheets'], function () {
      Route::get('/', [TimesheetController::class, 'getAll']);
      Route::get('/{id}', [TimesheetController::class,'get']);
      Route::post('/create', [TimesheetController::class,'create']);
      Route::put('/update/{id}', [TimesheetController::class, 'update']);
      Route::delete('/delete/{id}', [TimesheetController::class, 'delete']);
    });
  });

});
