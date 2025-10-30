<?php

// use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;
use App\Livewire\Module\DashboardLivewire;
use App\Livewire\Module\TreeManagement\TreeIndexLivewire;
use App\Livewire\Module\UserManagement\RoleIndexLivewire;
use App\Livewire\Module\UserManagement\UserIndexLivewire;
use App\Livewire\Module\TreeManagement\TreeDetailsLivewire;
use App\Livewire\Module\UserManagement\UserProfileLivewire;
use App\Livewire\Module\TreeManagement\DiseaseIndexLivewire;
use App\Livewire\Module\TreeManagement\SpeciesIndexLivewire;
use App\Livewire\Module\TreeManagement\TreeGrowthLogLivewire;
use App\Livewire\Module\PostHarvest\HarvestEventIndexLivewire;
use App\Livewire\Module\UserManagement\PermissionIndexLivewire;
use App\Livewire\Module\SalesAndTransactions\BuyerIndexLivewire;
use App\Livewire\Module\TreeManagement\TreeHealthRecordLivewire;
use App\Livewire\Module\UserManagement\ActivityLogIndexLivewire;
use App\Livewire\Module\PostHarvest\HarvestEventOverviewLivewire;
use App\Livewire\Module\SalesAndTransactions\BuyerOverviewLivewire;
use App\Livewire\Module\SalesAndTransactions\TransactionIndexLivewire;
use App\Livewire\Module\PostHarvest\HarvestEventHarvestSummaryLivewire;
use App\Livewire\Module\SalesAndTransactions\CreateTransactionLivewire;
use App\Livewire\Module\AgrochemicalManagement\AgrochemicalIndexLivewire;
use App\Livewire\Module\AgrochemicalManagement\AgrochemicalOverviewLivewire;
use App\Livewire\Module\AgrochemicalManagement\AgrochemicalPurchaseHistoryLivewire;
use App\Livewire\Module\TreeManagement\TreeHarvestRecordLivewire;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', DashboardLivewire::class)->name('dashboard');

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/all', UserIndexLivewire::class)->name('users.index');
        Route::group(['prefix' => 'role'], function () {
            Route::get('/all', RoleIndexLivewire::class)->name('roles.index');
        });
        Route::group(['prefix' => 'permission'], function () {
            Route::get('/all', PermissionIndexLivewire::class)->name('permissions.index');
        });
    });

    Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
        Route::get('/settings', UserProfileLivewire::class)->name('settings');
    });

    Route::group(['prefix' => 'tree', 'as' => 'tree.'], function () {
        Route::get('/disease', DiseaseIndexLivewire::class)->name('disease.index');
        Route::get('/species', SpeciesIndexLivewire::class)->name('species.index');
        Route::get('/all', TreeIndexLivewire::class)->name('trees.index');
        Route::group(['prefix' => 'details/{tree:id}'], fn() => [
            Route::get('overview', TreeDetailsLivewire::class)->name('show'),
            Route::get('growth-log', TreeGrowthLogLivewire::class)->name('growth-log'),
            Route::get('health-record', TreeHealthRecordLivewire::class)->name('health-record'),
            Route::get('harvest-record', TreeHarvestRecordLivewire::class)->name('harvest-record'),
        ]);
    });

    Route::group(['prefix' => 'agrochemical', 'as' => 'agrochemical.'], function () {
        Route::get('/all', AgrochemicalIndexLivewire::class)->name('agrochemicals.index');
        Route::group(['prefix' => 'details/{agrochemical:id}'], fn() => [
            Route::get('overview', AgrochemicalOverviewLivewire::class)->name('show'),
            Route::get('purchase-history', AgrochemicalPurchaseHistoryLivewire::class)->name('purchase-history'),
        ]);
    });

    Route::group(['prefix' => 'harvest', 'as' => 'harvest.'], function () {
        Route::get('/all', HarvestEventIndexLivewire::class)->name('events.index');
        Route::group(['prefix' => 'details/{harvestEvent:id}'], fn() => [
            Route::get('overview', HarvestEventOverviewLivewire::class)->name('show'),
            Route::get('harvest-summary', HarvestEventHarvestSummaryLivewire::class)->name('harvest-summary'),
        ]);
    });

    Route::group(['prefix' => 'sales', 'as' => 'sales.'], function () {
        Route::group(['prefix' => 'buyer'], function () {
            Route::get('/all', BuyerIndexLivewire::class)->name('buyers.index');
            Route::get('details/{buyer:id}', BuyerOverviewLivewire::class)->name('buyers.show');
        });

        Route::group(['prefix' => 'transaction'], function () {
            Route::get('/all', TransactionIndexLivewire::class)->name('transaction.index');
            Route::get('create', CreateTransactionLivewire::class)->name('transaction.create');
        });
    });

    Route::group(['prefix' => 'log', 'as' => 'log.'], function () {
        Route::get('/all', ActivityLogIndexLivewire::class)->name('logs.index');
    });

    Route::view('/welcome', 'welcome')->name('welcome');
});

require __DIR__ . '/auth.php';
