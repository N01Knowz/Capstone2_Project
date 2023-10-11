<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\essayTestbankController;
use App\Http\Controllers\matchingTestbankController;
use App\Http\Controllers\mcqTestbankController;
use App\Http\Controllers\tfTestbankController;
use App\Http\Controllers\mtfTestbankController;
use App\Http\Controllers\enumerationTestbankController;
use App\Http\Controllers\testMakerController;
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
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/new_password', [ProfileController::class, 'new_password'])->name('profile.new_password');
    Route::put('/profile/new_password', [ProfileController::class, 'update_password'])->name('profile.update_password');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('essay', essayTestbankController::class);

Route::resource('mcq', mcqTestbankController::class);
Route::get('/mcq/{test_id}/create_question', [mcqTestbankController::class, 'add_question_index']);
Route::post('/mcq/{test_id}/create_question', [mcqTestbankController::class, 'add_question_store']);
Route::get('/mcq/{test_id}/{question_id}', [mcqTestbankController::class, 'add_question_show']);
Route::put('/mcq/{test_id}/{question_id}/edit', [mcqTestbankController::class, 'add_question_update']);
Route::get('/mcq/{test_id}/{question_id}/edit', [mcqTestbankController::class, 'add_question_edit']);
Route::delete('/mcq/{question_id}/delete_question', [mcqTestbankController::class, 'add_question_destroy']);

// Route::get('/test', function(){
//     return view('testview');
// });

Route::resource('tf', tfTestbankController::class);
Route::get('/tf/{test_id}/create_question', [tfTestbankController::class, 'add_question_index']);
Route::post('/tf/{test_id}/create_question', [tfTestbankController::class, 'add_question_store']);
Route::get('/tf/{test_id}/{question_id}', [tfTestbankController::class, 'add_question_show']);
Route::put('/tf/{test_id}/{question_id}/edit', [tfTestbankController::class, 'add_question_update']);
Route::get('/tf/{test_id}/{question_id}/edit', [tfTestbankController::class, 'add_question_edit']);
Route::delete('/tf/{question_id}/delete_question', [tfTestbankController::class, 'add_question_destroy']);

Route::resource('mtf', mtfTestbankController::class);
Route::get('/mtf/{test_id}/create_question', [mtfTestbankController::class, 'add_question_index']);
Route::post('/mtf/{test_id}/create_question', [mtfTestbankController::class, 'add_question_store']);
Route::get('/mtf/{test_id}/{question_id}', [mtfTestbankController::class, 'add_question_show']);
Route::put('/mtf/{test_id}/{question_id}/edit', [mtfTestbankController::class, 'add_question_update']);
Route::get('/mtf/{test_id}/{question_id}/edit', [mtfTestbankController::class, 'add_question_edit']);
Route::delete('/mtf/{question_id}/delete_question', [mtfTestbankController::class, 'add_question_destroy']);

Route::resource('matching', matchingTestbankController::class);
Route::get('/matching/{test_id}/create_question', [matchingTestbankController::class, 'add_question_index']);
Route::post('/matching/{test_id}/create_question', [matchingTestbankController::class, 'add_question_store']);
Route::get('/matching/{test_id}/{question_id}', [matchingTestbankController::class, 'add_question_show']);
Route::put('/matching/{test_id}/{question_id}/edit', [matchingTestbankController::class, 'add_question_update']);
Route::get('/matching/{test_id}/{question_id}/edit', [matchingTestbankController::class, 'add_question_edit']);
Route::delete('/matching/{question_id}/delete_question', [matchingTestbankController::class, 'add_question_destroy']);

Route::resource('enumeration', enumerationTestbankController::class);
Route::post('/enumeration/{test_id}/create_question', [enumerationTestbankController::class, 'add_question_store']);
Route::delete('/enumeration/{question_id}/delete_question', [enumerationTestbankController::class, 'add_question_destroy']);

Route::resource('test', testMakerController::class);
Route::delete('/test/{test_id}/{test_maker_id}/delete', [testMakerController::class, 'destroy_question']);
Route::get('/test/{test_id}/{test_type}', [testMakerController::class, 'add_test_index']);
Route::post('/test/{test_id}/{test_type}', [testMakerController::class, 'add_test_store']);
Route::post('/test/{test_id}/{test_type}/add', [testMakerController::class, 'random_test_store']);

Route::get('/testpage', function(){
    return view('testview');
});


require __DIR__.'/auth.php';
