<?php

use App\Http\Controllers\Cms\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cms Routes
|--------------------------------------------------------------------------
|
| Here is where you can register cms routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "cms" middleware group. Now create something great!
|
*/

Route::as('auth.')->group(function () {
  Route::get('login', [AuthController::class, 'getUserTheLoginView'])->name('login');
});
