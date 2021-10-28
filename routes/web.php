<?php

use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\UserController;

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
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [UserController::class, 'createUser']);
Route::get('/employee', function () {
    return view('employees');
})->name('employee');
Route::get('/categories', function () {
    return view('categories');
})->name('categories');
Route::get('/employee_records', function () {
    return view('employee_records');
})->name('employee_records');


