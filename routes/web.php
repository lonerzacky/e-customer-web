<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

Route::get('/login', function () {
    if (request()->cookie('jwt_exists') || !empty($_COOKIE['jwt_exists'])) {
        return redirect('/dashboard');
    }
    return view('auth.login');
})->name('login');

Route::get('/logout', function () {
    return redirect('/login')
        ->withoutCookie('jwt_exists');
})->name('logout');


Route::prefix('/')->group(function () {
    Route::view('/dashboard', 'dashboard.index')->name('dashboard');
    Route::view('/profile', 'profile.index')->name('profile');

    Route::prefix('rekening/tabungan')->group(function () {
        Route::view('/', 'rekening.tabungan.index')->name('tabungan.index');           // daftar
        Route::view('/detail', 'rekening.tabungan.detail')->name('tabungan.detail');   // detail?norek=
    });

    Route::prefix('rekening/deposito')->group(function () {
        Route::view('/', 'rekening.deposito.index')->name('deposito.index');
        Route::view('/detail', 'rekening.deposito.detail')->name('deposito.detail');
    });

    Route::prefix('rekening/kredit')->group(function () {
        Route::view('/', 'rekening.kredit.index')->name('kredit.index');
        Route::view('/detail', 'rekening.kredit.detail')->name('kredit.detail');
    });
});
