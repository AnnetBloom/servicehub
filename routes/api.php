<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use \App\Http\Controllers\Api\ContactController;
use \App\Http\Controllers\Api\SiteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('companies', CompanyController::class);
Route::apiResource('contacts', ContactController::class);
Route::apiResource('sites', SiteController::class);




