<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Auth\GmailController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
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

Route::group(['prefix' => 'profile/gmail', 'middleware' => 'auth'], function () {
    Route::get('/', [GmailController::class, 'home']);
    Route::get('/oauth/gmail', [GmailController::class, 'loginGmail']);
    Route::post('/list-profile', [GmailController::class, 'listProfile']);
    Route::get('/get/newest', [GmailController::class, 'getGmailNewest']);
    Route::post('/store', [GmailController::class, 'store']);
    // Route::get('/list-profile', [GmailController::class, 'listCV']);
    
    Route::get('/oauth/gmail/callback', function (){
        LaravelGmail::makeToken();
        return redirect()->to('profile/gmail');
    });

    Route::get('/oauth/gmail/logout', function (){
        LaravelGmail::logout(); //It returns exception if fails
        return redirect()->to('profile/gmail');
    });
});

Route::group(['prefix' => 'jobs', 'middleware' => 'auth'], function () {
    Route::get('/add', [JobController::class, 'add']);
    Route::post('/store', [JobController::class, 'store'])->name('admin.jobs.store');
    Route::get('list', [JobController::class, 'list']);
    Route::get('{id}/detail', [JobController::class, 'show']);
    Route::post('{id}/updateDetail', [JobController::class, 'updateDetail']);
    Route::get('{id}/edit', [JobController::class, 'edit'])->name('admin.jobs.edit');
    Route::post('{id}/update', [JobController::class, 'update'])->name('admin.jobs.update');
    Route::get('{id}/delete', [JobController::class, 'destroy']);
    Route::post('/importResonse', [JobController::class, 'importResponses']);
    Route::post('/storeResonse', [JobController::class, 'storeResponses']);
    Route::post('/check-profiles', [JobController::class, 'checkListProfile']);
    Route::post('/save-profiles', [JobController::class, 'saveListProfile']);
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

Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
    Route::get('add', [UserController::class, 'add']);
    Route::post('/store', [UserController::class, 'store'])->name('admin.users.store');
    // Route::get('{id}/detail', [UserController::class, 'show']);
    // Route::post('{id}/updateDetail', [UserController::class, 'updateDetail']);
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('{id}/update', [UserController::class, 'update'])->name('admin.users.update');
    Route::get('{id}/delete', [UserController::class, 'destroy']);
    Route::get('list', [UserController::class, 'list']);
});

Route::group(['prefix' => 'profile', 'middleware' => 'auth'], function () {
    Route::get('edit', [AuthController::class, 'edit']);
    Route::post('/update', [AuthController::class, 'update'])->name('admin.users.update');
});