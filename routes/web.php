<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CatalogController;

Route::get('/Catalog', [CatalogController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});
