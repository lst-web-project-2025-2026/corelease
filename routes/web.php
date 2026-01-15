<?php

use Illuminate\Support\Facades\Route;

use App\Models\Resource;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Setting;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication & Application Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/apply', [AuthController::class, 'showRegister'])->name('register');
Route::post('/apply', [AuthController::class, 'register']);
Route::post('/check-status', [AuthController::class, 'checkStatus'])->name('status.check');

// Resource Catalog
Route::get('/catalog', [CatalogController::class, 'browse'])->name('catalog.index');
