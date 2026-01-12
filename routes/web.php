<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Applications; 

use App\Http\Controllers\CatalogController;

use App\Http\Controllers\ApplicationController ;

Route::get('/Catalog', [CatalogController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});


Route::get('/Applications', [ApplicationController::class, 'create'])->name('applications.create');
Route::post('/Applications', [ApplicationController::class, 'store'])->name('applications.store');