<?php

// use App\Http\Controllers\ProfileController;

use App\Livewire\Module\DashboardLivewire;
use Illuminate\Support\Facades\Route;
use App\Livewire\Module\UserManagement\UserIndexLivewire;
use App\Livewire\Module\TreeManagement\SpeciesIndexLivewire;
use App\Livewire\TreeManagement\TreeIndexLivewire;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', DashboardLivewire::class)->name('dashboard');

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/all', UserIndexLivewire::class)->name('users.index');
    });

    Route::group(['prefix' => 'tree', 'as' => 'tree.'], function () {
        Route::get('/species', SpeciesIndexLivewire::class)->name('species.index');
        Route::get('/all', TreeIndexLivewire::class)->name('trees.index');

    });

    Route::view('/welcome', 'welcome')->name('welcome');

});

require __DIR__.'/auth.php';

