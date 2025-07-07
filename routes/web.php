<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});


Route::middleware('auth')->group(function () {


    Route::post('/change-language', [\App\Http\Controllers\LanguageController::class, 'changeLanguage'])->name('change-language');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/projects',  function () {
        return Inertia::render('Project/Index');
    });
    Route::get('/projects/create',  function () {
        return Inertia::render('Project/Create');
    });
    Route::get('/settings',  function () {
        return Inertia::render('Settings/General');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
