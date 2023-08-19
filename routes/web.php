<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\essayTestbankController;
use App\Http\Controllers\matchingTestbankController;
use App\Http\Controllers\mcqTestbankController;
use App\Http\Controllers\tfTestbankController;
use App\Http\Controllers\mtfTestbankController;
use App\Http\Controllers\enumerationTestbankController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('essay', essayTestbankController::class);

Route::resource('mcq', mcqTestbankController::class);

Route::get('/test', function(){
    return view('testview');
});

Route::get('/mcq/{test_id}/create_question', [mcqTestbankController::class, 'add_question_index']);
Route::post('/mcq/{test_id}/create_question', [mcqTestbankController::class, 'add_question_store']);
Route::delete('/mcq/{question_id}/delete_question', [mcqTestbankController::class, 'add_question_destroy']);

Route::resource('tf', tfTestbankController::class);

Route::get('/tf/question/add', function () {
    return view('testbank/tf/tf_add_question');
});

Route::resource('mtf', mtfTestbankController::class);

Route::get('/mtf/question/add', function () {
    return view('testbank/mtf/mtf_add_question');
});

Route::resource('matching', matchingTestbankController::class);

Route::get('/matching/{str}/description', function () {
    return view('testbank/matching/matching_test-description');
});

Route::get('/matching/question/add', function () {
    return view('testbank/matching/matching_add_question');
});

Route::resource('enumeration', enumerationTestbankController::class);

Route::get('/enumeration/{str}/description', function () {
    return view('testbank/enumeration/enumeration_test-description');
});

Route::get('/enumeration/question/add', function () {
    return view('testbank/enumeration/enumeration_add_question');
});



require __DIR__.'/auth.php';
