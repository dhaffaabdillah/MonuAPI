<?php

use App\Http\Controllers\API\AnswerQuestionController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ClassController;
use App\Http\Controllers\API\ExamController;
use App\Http\Controllers\API\ExamPackagesController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\QuestionMoodController;
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
    Route::apiResource('user', UserController::class)->middleware('auth:sanctum')->except(['except']);
    Route::patch('user/{id}', [UserController::class, 'updateProfileByAdmin'])->middleware('auth:sanctum');
    Route::post('user/update-profile', [UserController::class, 'updateProfileBySession'])->middleware('auth:sanctum');
    
    Route::group(['prefix' => 'auth'], function(){
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    });

    // aku ingin punya porsche 911 gt3 rs
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::get('/profile', [AuthController::class, 'getCurrentSession']);
        Route::apiResource('subject', SubjectController::class)->except('update');
        Route::patch('subject/{id}', [SubjectController::class, 'update']);
        Route::resource('teacher', TeacherController::class)->except('update');
        Route::post('teacher/{id}', [TeacherController::class, 'update']);
        Route::apiResource('exam', ExamController::class)->except('update');
        Route::patch('exam/{id}', [ExamController::class, 'update']);
        Route::apiResource('exam-package', ExamPackagesController::class)->except('update');
        Route::get('exam-package/exam/{exam_id}', [ExamPackagesController::class, 'fetchExam']);
        Route::post('exam-package/{id}', [ExamPackagesController::class, 'update']);
        Route::apiResource('question',  QuestionController::class)->except('update');
        Route::post('question/{id}', [QuestionController::class, 'update']);
        Route::apiResource('teacher-subject', TeacherSubjectController::class)->except('update');
        Route::patch('teacher-subject/{id}', [TeacherSubjectController::class, 'update']);
        Route::get('exam/take-exam', [AnswerQuestionController::class, 'index']);
        Route::post('exam/take-exam/{exam_id}', [AnswerQuestionController::class, 'saveAnswer']);
        Route::apiResource('classes', ClassController::class);
        // logout function
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all', [AuthController::class, 'logoutAll'])->middleware('Admin');
        Route::apiResource('question-mood', QuestionMoodController::class)->except('update');
        Route::post('question-mood/{id}', [QuestionMoodController::class, 'update']);
        Route::get('take-question-mood/results', [QuestionMoodController::class, 'index']);
        Route::get('take-question-mood/{take_exam_id}', [QuestionMoodController::class, 'store']);

        // for pagination or searching
        Route::get('/user{user?}', [UserController::class,'index']);
        Route::get('/search-user{user?}', [UserController::class, 'search']);

        // code to need refactor LOL
        Route::get('user-with-teacher-role', [TeacherController::class, 'getDataUserOnlyTeacher']);
    });
    

});
