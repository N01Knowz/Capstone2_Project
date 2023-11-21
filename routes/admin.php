<?php

use App\Http\Controllers\manageAccountsController;
use Illuminate\Support\Facades\Route;


Route::get('/accounts', [manageAccountsController::class, 'index']);
Route::put('/accounts/activate/{id}', [manageAccountsController::class, 'activate']);
Route::put('/accounts/deactivate/{id}', [manageAccountsController::class, 'deactivate']);
Route::delete('/accounts/{id}/delete', [manageAccountsController::class, 'destroy']);
