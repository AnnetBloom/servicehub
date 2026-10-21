<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use App\Models\Company;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(CompanyController::class)->group(function() {
    Route::get('/companies', 'index');
    Route::get('/companies/{company}', 'show');
    Route::post('/companies', 'store');
});



