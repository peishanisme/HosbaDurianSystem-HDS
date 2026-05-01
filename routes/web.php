<?php

use App\Http\Controllers\ReportController;
use App\Livewire\Module\{ DashboardLivewire, PublicPortalLivewire, AgrochemicalManagement\AgrochemicalApplicationRecordLivewire, AgrochemicalManagement\AgrochemicalGlobalUsageLivewire, AgrochemicalManagement\AgrochemicalIndexLivewire, AgrochemicalManagement\AgrochemicalOverviewLivewire, AgrochemicalManagement\AgrochemicalPurchaseHistoryLivewire, PostHarvest\HarvestEventHarvestSummaryLivewire, PostHarvest\HarvestEventIndexLivewire, PostHarvest\HarvestEventOverviewLivewire, SalesAndTransactions\BuyerIndexLivewire, SalesAndTransactions\BuyerTransactionLivewire, SalesAndTransactions\CreateTransactionLivewire, SalesAndTransactions\TransactionIndexLivewire, SalesAndTransactions\BuyerOverviewLivewire, TreeManagement\DiseaseIndexLivewire, TreeManagement\SpeciesIndexLivewire, TreeManagement\TreeAgrochemicalUsageLivewire, TreeManagement\TreeDetailsLivewire, TreeManagement\TreeGrowthLogLivewire, TreeManagement\TreeHarvestRecordLivewire, TreeManagement\TreeIndexLivewire, TreeManagement\TreeHealthRecordLivewire, UserManagement\ActivityLogIndexLivewire, UserManagement\PermissionIndexLivewire, UserManagement\RoleIndexLivewire, UserManagement\UserIndexLivewire, UserManagement\UserProfileLivewire };
use App\Livewire\Module\TreeManagement\TreeFeedbackLivewire;
use Illuminate\Support\Facades\Route;

//translation route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'zh', 'ms'])) {
        session(['locale' => $locale]);
    }

    return redirect(request('redirect', '/'));
})->name('lang.switch');

//download report as pdf
Route::get('/reports/export', [ReportController::class, 'export'])
    ->name('report.export');

Route::get('/receipt/print/{transaction:uuid}', [ReportController::class, 'printReceipt'])->name('receipt.print');

Route::get('/print/qr/{tree}', [ReportController::class, 'printFruitLabels'])
    ->name('print.qr');
//web route
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
            Route::get('agrochemical-usage', TreeAgrochemicalUsageLivewire::class)->name('agrochemical-usage'),
            Route::get('feedback', TreeFeedbackLivewire::class)->name('feedback'),
        ]);
    });

    Route::group(['prefix' => 'agrochemical', 'as' => 'agrochemical.'], function () {
        Route::get('/all', AgrochemicalIndexLivewire::class)->name('agrochemicals.index');
        Route::get('/usage', AgrochemicalGlobalUsageLivewire::class)->name('agrochemicals.usage');
        Route::group(['prefix' => 'details/{agrochemical:id}'], fn() => [
            Route::get('overview', AgrochemicalOverviewLivewire::class)->name('show'),
            Route::get('purchase-history', AgrochemicalPurchaseHistoryLivewire::class)->name('purchase-history'),
            Route::get('application-record', AgrochemicalApplicationRecordLivewire::class)->name('application-record'),
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
            Route::group(['prefix' => 'details/{buyer:id}'], fn() => [
                Route::get('overview', BuyerOverviewLivewire::class)->name('buyers.show'),
                Route::get('transaction', BuyerTransactionLivewire::class)->name('buyers.transaction'),
            ]);
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

//public portal
Route::get('/product-details/{tree:uuid}', PublicPortalLivewire::class)->name('public.portal');



require __DIR__ . '/auth.php';
