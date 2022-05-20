<?php

use App\Http\Controllers\Admin\AnswerQuestionController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\MaterialsController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuestionMoodController;
use App\Http\Controllers\Admin\ExamPackagesController;
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
    // Route::resource('question-cognitive', QuestionController::class);
    Route::get('question-cognitive', [QuestionController::class,'index'])->name('qc-index');
    Route::get('question-cognitive/create', [QuestionController::class,'create'])->name('qc-create');
    Route::get('question-cognitive/{id}', [QuestionController::class,'edit'])->name('qc-edit');
    Route::post('question-cognitive', [QuestionController::class,'store'])->name('qc-store');
    Route::put('question-cognitive/{id}', [QuestionController::class,'update'])->name('qc-update');
    Route::delete('question-cognitive/{id}', [QuestionController::class,'destroy'])->name('qc-destroy');

    // Route::resource('question-mood', QuestionMoodController::class);
    Route::get('question-mood', [QuestionMoodController::class, 'index'])->name('qm-index');
    Route::get('question-mood/create', [QuestionMoodController::class, 'create'])->name('qm-create');
    Route::post('question-mood', [QuestionMoodController::class, 'store'])->name('qm-store');
    Route::get('question-mood/{id}', [QuestionMoodController::class, 'edit'])->name('qm-edit');
    Route::put('question-mood/{id}', [QuestionMoodController::class, 'update'])->name('qm-update');
    Route::delete('question-mood/{id}', [QuestionMoodController::class, 'destroy'])->name('qm-destroy');
    
    // Route::resource('exam-package', ExamPackagesController::class);
    Route::get('exam-package', [ExamPackagesController::class, 'index'])->name('ep-index');
    Route::get('exam-package/create', [ExamPackagesController::class, 'create'])->name('ep-create');
    Route::post('exam-package', [ExamPackagesController::class, 'store'])->name('ep-store');
    Route::get('exam-package/{id}', [ExamPackagesController::class, 'edit'])->name('ep-edit');
    Route::put('exam-package/{id}', [ExamPackagesController::class, 'update'])->name('ep-update');
    Route::delete('exam-package/{id}', [ExamPackagesController::class, 'destroy'])->name('ep-destroy');

    Route::get('exam', [ExamController::class, 'index'])->name('e-index');
    Route::get('exam/create', [ExamController::class, 'create'])->name('e-create');
    Route::post('exam', [ExamController::class, 'store'])->name('e-store');
    Route::get('exam/{id}', [ExamController::class, 'edit'])->name('e-edit');
    Route::put('exam/{id}', [ExamController::class, 'update'])->name('e-update');
    Route::delete('exam/{id}', [ExamController::class, 'destroy'])->name('e-destroy');
    
    // Route::resource('exam', ExamPackagesController::class);
    Route::get('materials', [MaterialsController::class, 'index'])->name('material-index');
    Route::post('materials', [MaterialsController::class, 'store'])->name('material-store');
    Route::get('materials/{id}', [MaterialsController::class, 'edit'])->name('material-edit');
    Route::put('materials/{id}', [MaterialsController::class, 'update'])->name('material-update');
    Route::delete('materials/{id}', [MaterialsController::class, 'destroy'])->name('material-destroy');
    // Route::resource('materials', MaterialsController::class);

    Route::get('/logout', function() {
        Auth::logout();
        redirect('/');
    })->name('logout-admin');
});

