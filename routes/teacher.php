<?php

use App\Http\Controllers\essayTestbankController;
use App\Http\Controllers\matchingTestbankController;
use App\Http\Controllers\mcqTestbankController;
use App\Http\Controllers\tfTestbankController;
use App\Http\Controllers\mtfTestbankController;
use App\Http\Controllers\enumerationTestbankController;
use App\Http\Controllers\excelController;
use App\Http\Controllers\printPageController;
use App\Http\Controllers\testMakerController;
use Illuminate\Support\Facades\Route;

// Route::resource('essay', essayTestbankController::class);

Route::resource('mcq', mcqTestbankController::class);
Route::get('/mcq/{test_id}/create_question', [mcqTestbankController::class, 'add_question_index']);
Route::post('/mcq/{test_id}/create_question', [mcqTestbankController::class, 'add_question_store']);
Route::post('/mcq/{test_id}/create_multiple_questions', [mcqTestbankController::class, 'add_multiple_store']);
Route::get('/mcq/{test_id}/{question_id}', [mcqTestbankController::class, 'add_question_show']);
Route::put('/mcq/{test_id}/{question_id}/edit', [mcqTestbankController::class, 'add_question_update']);
Route::get('/mcq/{test_id}/{question_id}/edit', [mcqTestbankController::class, 'add_question_edit']);
Route::delete('/mcq/{question_id}/delete_question', [mcqTestbankController::class, 'add_question_destroy']);
Route::put('/mcq/{question_id}/publish', [mcqTestbankController::class, 'publish']);

Route::resource('tf', tfTestbankController::class);
Route::get('/tf/{test_id}/create_question', [tfTestbankController::class, 'add_question_index']);
Route::post('/tf/{test_id}/create_question', [tfTestbankController::class, 'add_question_store']);
Route::get('/tf/{test_id}/{question_id}', [tfTestbankController::class, 'add_question_show']);
Route::put('/tf/{test_id}/{question_id}/edit', [tfTestbankController::class, 'add_question_update']);
Route::get('/tf/{test_id}/{question_id}/edit', [tfTestbankController::class, 'add_question_edit']);
Route::delete('/tf/{question_id}/delete_question', [tfTestbankController::class, 'add_question_destroy']);
Route::post('/tf/{test_id}/create_multiple_questions', [tfTestbankController::class, 'add_multiple_store']);
Route::put('/tf/{question_id}/publish', [tfTestbankController::class, 'publish']);

// Route::resource('mtf', mtfTestbankController::class);
// Route::get('/mtf/{test_id}/create_question', [mtfTestbankController::class, 'add_question_index']);
// Route::post('/mtf/{test_id}/create_question', [mtfTestbankController::class, 'add_question_store']);
// Route::get('/mtf/{test_id}/{question_id}', [mtfTestbankController::class, 'add_question_show']);
// Route::put('/mtf/{test_id}/{question_id}/edit', [mtfTestbankController::class, 'add_question_update']);
// Route::get('/mtf/{test_id}/{question_id}/edit', [mtfTestbankController::class, 'add_question_edit']);
// Route::delete('/mtf/{question_id}/delete_question', [mtfTestbankController::class, 'add_question_destroy']);
// Route::post('/mtf/{test_id}/create_multiple_questions', [mtfTestbankController::class, 'add_multiple_store']);
// Route::put('/mtf/{question_id}/publish', [mtfTestbankController::class, 'publish']);

Route::resource('matching', matchingTestbankController::class);
Route::get('/matching/{test_id}/create_question', [matchingTestbankController::class, 'add_question_index']);
Route::post('/matching/{test_id}/create_question', [matchingTestbankController::class, 'add_question_store']);
Route::get('/matching/{test_id}/{question_id}', [matchingTestbankController::class, 'add_question_show']);
Route::put('/matching/{test_id}/{question_id}/edit', [matchingTestbankController::class, 'add_question_update']);
Route::get('/matching/{test_id}/{question_id}/edit', [matchingTestbankController::class, 'add_question_edit']);
Route::delete('/matching/{question_id}/delete_question', [matchingTestbankController::class, 'add_question_destroy']);
Route::post('/matching/{test_id}/create_multiple_questions', [matchingTestbankController::class, 'add_multiple_store']);
Route::put('/matching/{question_id}/publish', [matchingTestbankController::class, 'publish']);

Route::resource('enumeration', enumerationTestbankController::class);
Route::post('/enumeration/{test_id}/create_question', [enumerationTestbankController::class, 'add_question_store']);
Route::delete('/enumeration/{question_id}/delete_question', [enumerationTestbankController::class, 'add_question_destroy']);
Route::post('/enumeration/{test_id}/create_multiple_questions', [enumerationTestbankController::class, 'add_multiple_store']);
Route::put('/enumeration/{question_id}/publish', [enumerationTestbankController::class, 'publish']);

Route::resource('test', testMakerController::class);
Route::delete('/test/{test_type}/{test_id}/{test_maker_id}/delete', [testMakerController::class, 'destroy_question']);
Route::get('/test/{test_id}/{test_type}', [testMakerController::class, 'add_test_index']);
Route::post('/test/{test_id}/{test_type}', [testMakerController::class, 'add_test_store']);
Route::post('/test/{test_id}/{test_type}/add', [testMakerController::class, 'random_test_store']);
Route::put('/test/{question_id}/publish', [testMakerController::class, 'publish']);

Route::get('/print/{test_type}/{test_id}', [printPageController::class, 'testPage']);

Route::get('/mcq-excel', [excelController::class, 'mcq'])->name('mcq-excel');
Route::get('/tf-excel', [excelController::class, 'tf'])->name('tf-excel');
Route::get('/mtf-excel', [excelController::class, 'mtf'])->name('mtf-excel');
Route::get('/matching-excel', [excelController::class, 'matching'])->name('matching-excel');
Route::get('/enumeration-excel', [excelController::class, 'enumeration'])->name('enumeration-excel');