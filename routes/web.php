<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRecordsController;

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

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
});
Route::post('/register', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);
Route::get('/logout', [AuthController::class, 'logoutUser']);

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::get('/employee', [UserController::class, 'getEmployees'])->name('employee')->middleware('onlyManagerAllowed');
    Route::get('/employee/{userId}', [UserRecordsController::class, 'getEmployeeRecords']);
    Route::post('/employee', [UserController::class, 'createEmployee']);
    Route::delete('/employee/{user}', [UserController::class, 'deleteEmployee']);

    Route::get('/categories/{categoryId}', [UserRecordsController::class, 'getRecordsByCategory'])->name('categories');

    Route::get('/employee_records', [UserRecordsController::class, 'getRecords'])->name('employee_records');
    Route::get('/employee_records_details/{user_record}', [UserRecordsController::class, 'showRecord']);
    Route::post('/employee_records', [UserRecordsController::class, 'createRecord']);
    Route::delete('/employee_records/{user_record}', [UserRecordsController::class, 'deleteRecord']);
    Route::get('/employee_records/{user_record}', [UserRecordsController::class, 'getRecord']);

});
