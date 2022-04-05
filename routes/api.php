<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ExamController;
use App\Http\Controllers\API\ExamPackagesController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\API\TeacherSubjectController;
use App\Http\Controllers\API\UserController;
use App\Http\Middleware\Role;
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
    Route::apiResource('user', UserController::class)->middleware('auth:sanctum');
    
    Route::group(['prefix' => 'auth'], function(){
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    });

    // aku ingin punya porsche 911 gt3 rs
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::get('/profile', [AuthController::class, 'getCurrentSession']);
        Route::apiResource('subject', SubjectController::class);
        Route::apiResource('teacher', TeacherController::class);
        Route::apiResource('exams', ExamController::class);
        Route::apiResource('exam-packages', ExamPackagesController::class);
        Route::apiResource('questions',  QuestionController::class);
        Route::apiResource('teacher-subjects', TeacherSubjectController::class);
        // logout function
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all', [AuthController::class, 'logoutAll'])->middleware('Admin');

        // for pagination or searching
        Route::get('/user{user?}', [UserController::class,'index']);
        Route::get('/search-user{user?}', [UserController::class, 'search']);

        // code to need refactor LOL
        Route::get('user-with-teacher-role', [TeacherController::class, 'getDataUserOnlyTeacher']);
    });
    

});
