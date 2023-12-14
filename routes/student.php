<?php

use App\Http\Controllers\analyticsController;
use App\Http\Controllers\takeTestController;
use Illuminate\Support\Facades\Route;

Route::get('/taketest', [takeTestController::class, 'index'])->name('taketestIndex');
Route::get('/taketest/{type}/{id}/{creatorID}/test', [takeTestController::class, 'taketest']);
Route::get('/taketest/{type}/{id}/finish', [takeTestController::class, 'finishtest'])->name('finish-test');
Route::get('/taketest/{type}/{id}/result', [takeTestController::class, 'seeresult']);

Route::get('/analytics', [analyticsController::class, 'index']);
Route::get('/analytics/riasec', [analyticsController::class, 'riasec']);