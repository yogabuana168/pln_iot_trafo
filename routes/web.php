<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Root -> login
Route::get('/', function () {
    return redirect('/login');
});

// Login (view only)
Route::get('/login', function () {
    return view('auth-signin');
})->name('login');

// Handle login (dummy session-based, no DB)
Route::post('/login', function (Request $request) {
    $request->session()->put('logged_in', true);
    return redirect('/dashboard');
});

// Dashboard (protected via simple session check)
Route::get('/dashboard', function (Request $request) {
    if (!$request->session()->get('logged_in')) {
        return redirect('/login');
    }
    return view('dashboard');
});

// Logout
Route::post('/logout', function (Request $request) {
    $request->session()->flush();
    return redirect('/login');
});
