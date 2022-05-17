<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

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
Route::get('/', [HomeController::class, 'index'])->middleware('auth');
Route::get('/home/filter/{id}', [HomeController::class, 'filterHome'])->middleware('auth');
Route::get('home/update/{id}', [HomeController::class, 'updateStatusProfile'])->middleware('auth');

Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Google URL
Route::get('login/google', [AuthController::class, 'redirectGoogle'])->name('login.google');



Route::group(['prefix' => 'jobs', 'middleware' => 'auth'], function () {
    Route::get('/add', [JobController::class, 'add']);
    Route::post('/store', [JobController::class, 'store'])->name('admin.jobs.store');
    Route::get('list', [JobController::class, 'list']);
    Route::get('{id}/detail', [JobController::class, 'show']);
    Route::post('{id}/updateDetail', [JobController::class, 'updateDetail']);
    Route::get('{id}/edit', [JobController::class, 'edit'])->name('admin.jobs.edit');
    Route::post('{id}/update', [JobController::class, 'update'])->name('admin.jobs.update');
    Route::get('{id}/delete', [JobController::class, 'destroy']);
});

Route::group(['prefix' => 'profile', 'middleware' => 'auth'], function () {
    Route::get('/add', [ProfileController::class, 'add']);
    Route::post('/store', [ProfileController::class, 'store'])->name('admin.cv.store');
    Route::get('/{id}/edit', [ProfileController::class, 'edit'])->name('admin.cv.edit');
    Route::post('/{id}/update', [ProfileController::class, 'update'])->name('admin.cv.update');
    Route::get('/{id}/delete', [ProfileController::class, 'destroy']);
    Route::get('list', [ProfileController::class, 'list']);
    Route::get('/list/{id}', [ProfileController::class, 'listCvByJob']);
    Route::get('{id}/detail', [ProfileController::class,'detail']);
    Route::post('{id}/updateDetail', [ProfileController::class, 'updateDetail']);
    Route::post('/storeInterviewResult', [ProfileController::class, 'storeInterviewResult']);
});