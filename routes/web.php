<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CompanyController;

Route::get('/', function () {
    //return view('welcome');
    return 'Hello';
    //return 'Hello, ' . auth()->user()->name;
});

Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function() {
    //dd(request()->all());
    $credentials = request()->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        request()->session()->regenerate();
        return redirect()->route('dashboard');
    }
   // dd('no');

    return back()->withErrors([
        'email' => 'Неверный логин или пароль.',
    ]);
})->name('login.store');

Route::middleware('auth')->group(function(){
    Route::get('/dashboard', function(){
        return view('dashboard');
    })->name('dashboard');

    Route::get('/companies', [CompanyController::class, 'index'])
        ->name('companies.index');
    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->name('companies.show');
});

