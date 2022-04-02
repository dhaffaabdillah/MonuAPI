<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\API\TeacherSubjectController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user()->name;
    return Auth::check();
});

// Auth::routes();
Route::prefix('v1')->group(function() {
    Route::resource('user', UserController::class)->middleware('auth:sanctum');

    Route::group(['prefix' => 'auth'], function(){

        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        
        Route::group(['middleware' => 'auth:sanctum'], function() {
            Route::resource('subject', SubjectController::class);
            Route::resource('teachers', TeacherController::class);
            Route::get('user-with-teacher-role', [TeacherController::class, 'getDataUserOnlyTeacher']);
            Route::resource('teacher-subjects', TeacherSubjectController::class);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('logout-all', [AuthController::class, 'logoutAll']);
        });
        
    });
    

});
