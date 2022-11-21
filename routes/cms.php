<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Cms\EventsController;
use App\Http\Controllers\Cms\GroupsController;
use App\Http\Controllers\Cms\TicketController;
use App\Http\Controllers\Cms\UsersController;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\AuthMiddleware;

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

Route::middleware(AuthMiddleware::class)->group(function () {
  Route::resources(['groups' => GroupsController::class]);
  Route::resources(['users' => UsersController::class]);
  Route::resources(['events' => EventsController::class]);
  Route::resources(['tickets' => TicketController::class]);
});
