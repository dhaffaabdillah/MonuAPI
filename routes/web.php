<?php

use App\Http\Controllers\Admin\AnswerQuestionController;
use App\Http\Controllers\Admin\MaterialsController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuestionMoodController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes(['login', 'register'=> true]);
Route::middleware(['auth'])->group(function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('question-cognitive', QuestionController::class);
    Route::resource('question-mood', QuestionMoodController::class);
    Route::resource('materials', MaterialsController::class);

    Route::get('/logout', function() {
        Auth::logout();
        redirect('/');
    })->name('logout-admin');
});

