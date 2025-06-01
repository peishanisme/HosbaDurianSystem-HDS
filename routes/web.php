<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\DashboardLivewire;
use App\Livewire\UserIndex2Livewire;
use App\Livewire\UserManagement\UserIndexLivewire;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', DashboardLivewire::class)->name('dashboard');

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/all', UserIndexLivewire::class)->name('users.index');
    });

    Route::view('/welcome', 'welcome')->name('welcome');

});

require __DIR__.'/auth.php';

