<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;

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

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('jwt.verify')->prefix('v1')->group(function() {
    Route::post('logout', [AuthController::class, 'logout']);

    /********* Department ***********/ 
    Route::post('departments/store', [DepartmentController::class, 'store']);
    Route::put('departments/{department}/update', [DepartmentController::class, 'update']);
    Route::delete('departments/{department}/delete', [DepartmentController::class, 'destroy']);
    Route::post('departments/{department}/attach/{employee}/employee', [DepartmentController::class, 'attachEmployee']);

    /********** Employee *************/
    Route::post('employees/store', [EmployeeController::class, 'store']);
    Route::put('employees/{employee}/update', [EmployeeController::class, 'update']);
    Route::delete('employees/{employee}/delete', [EmployeeController::class, 'destroy']);
});

