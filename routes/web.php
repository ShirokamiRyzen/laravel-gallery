<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\FotoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/public/photo/preview/{id}/full', [HomeController::class, 'show'])->name('home.show');
Route::post('/public/photo/{id}/komentar', [HomeController::class, 'addKomentar'])->name('foto.komentar');
Route::post('/public/photo/{id}/like', [HomeController::class, 'likeFoto'])->name('foto.like');
Route::delete('/public/photo/{id}/unlike', [HomeController::class, 'unlikeFoto'])->name('foto.unlike');
Route::delete('/public/photo/delete-comment/{id}', [HomeController::class, 'deleteComment'])->name('foto.deleteComment');

// Proteksi route dengan 'auth' middleware
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [DashController::class, 'index'])->name('dashboard');

    //Album routes
    Route::get('/album', [AlbumController::class, 'index'])->name('album');
    Route::get('/album/{id}/show', [FotoController::class, 'show'])->name('album.show');
    Route::get('/album/create', [AlbumController::class, 'create'])->name('album.create');
    Route::post('/album', [AlbumController::class, 'store'])->name('album.store');
    Route::get('album/{id}/edit', [AlbumController::class, 'edit'])->name('album.edit');
    Route::put('album/{id}', [AlbumController::class, 'update'])->name('album.update');
    Route::delete('/album/{album}', [AlbumController::class, 'destroy'])->name('album.destroy');

    //Foto routes
    Route::get('/photo', [FotoController::class, 'index'])->name('foto.index');
    Route::get('/photo/create', [FotoController::class, 'create'])->name('foto.create');
    Route::post('/photo', [FotoController::class, 'store'])->name('foto.store');
    Route::get('/photo/{id}/edit', [FotoController::class, 'edit'])->name('foto.edit');
    Route::put('/photo/{id}', [FotoController::class, 'update'])->name('foto.update');
    Route::delete('/photo/{id}', [FotoController::class, 'destroy'])->name('foto.destroy');
    Route::get('/photo/filter', [FotoController::class, 'filterByAlbum'])->name('foto.filterByAlbum');

    // Logout route (accessible only when authenticated)
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Profile picture routes
Route::put('/profile/update', [DashController::class, 'update'])->name('profile.update');
Route::delete('/profile/delete-picture', [DashController::class, 'deleteProfilePicture'])->name('profile.deletePicture');

// Protect routes using 'guest' middleware
Route::middleware(['guest'])->group(function () {
    // Register & Login form
    Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
