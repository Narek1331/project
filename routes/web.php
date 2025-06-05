<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/excel-editor', [ExcelController::class, 'index']);
});


Route::get('/login', function () {
    return redirect('/app/login');
})->name('login');
