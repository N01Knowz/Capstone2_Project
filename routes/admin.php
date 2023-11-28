<?php

use App\Http\Controllers\manageAccountsController;
use App\Http\Controllers\manageTestController;

use Illuminate\Support\Facades\Route;


Route::get('/accounts', [manageAccountsController::class, 'index']);
Route::put('/accounts/activate/{id}', [manageAccountsController::class, 'activate']);
Route::put('/accounts/deactivate/{id}', [manageAccountsController::class, 'deactivate']);
Route::delete('/accounts/{id}/delete', [manageAccountsController::class, 'destroy']);

Route::get('/managetest', [manageTestController::class, 'index']);
Route::put('/managetest/{type}/{id}/hide', [manageTestController::class, 'hide']);
