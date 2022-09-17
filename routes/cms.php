<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Cms\GroupsController;
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
  Route::get('login', [AuthController::class, 'getUserTheLoginView'])->name('get_login_view');
  Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'loginUser'])->name('log_user');
  });
});

Route::resources(['groups' => GroupsController::class]);
