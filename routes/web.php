<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminLoginGuardController;

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

Route::get('/dashboard', function () {
    return view('for_auth_user');
})->name('dashboard');
// ->middleware('auth');


Route::get('admin-login',[AdminLoginGuardController::class, 'adminLoginPage'])->name('adminLoginPage');
Route::post('admin-login',[AdminLoginGuardController::class, 'login'])->name('adminLogin');

Route::get('login',[LoginController::class, 'loginPage'])->name('loginPage');
Route::post('login',[LoginController::class, 'login'])->name('login');

Route::get('register',[LoginController::class, 'registerPage'])->name('registerPage');
Route::post('register',[LoginController::class, 'register'])->name('register');
