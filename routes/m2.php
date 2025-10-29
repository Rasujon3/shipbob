<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PaymentmethodController;
use App\Http\Controllers\CashoutController;
use App\Http\Controllers\ListController;

// Author: Ekram vai
Route::get('/ekram', function(){
	echo "Ekram Route";
});


Route::get('/migrate', function(){
	Artisan::call('migrate', [
        '--force' => true,
    ]);
    return response()->json(['message' => 'Migrations run successfully.']);
});

Route::get('/db-seed', function(){
    Artisan::call('db:seed', [
        '--force' => true,
    ]);
    return response()->json(['message' => 'Database seeded successfully.']);
});

Route::get('/test-route', function(){
	echo "test route";
});


Route::resource('paymentmethods', PaymentmethodController::class);
Route::post('check-withdraw-password', [PaymentmethodController::class, 'checkWithdraPassword']);

Route::get('/add-payment-method', [PaymentmethodController::class, 'addPaymentMethod']);

Route::get('/cashout', [CashoutController::class, 'cashout']);
Route::middleware(['check_site_status', 'user_auth'])->group(function () {
 Route::post('save-cashout', [CashoutController::class, 'saveCashOut']);
 Route::post('save-cashin', [CashoutController::class, 'saveCashIn']);
});

Route::middleware('admin_auth')->group(function () {
  Route::get('/cashin-lists', [ListController::class, 'cashinLists'])->name('cashin-lists');
  Route::get('/cashout-lists', [ListController::class, 'cashoutLists'])->name('cashout-lists');
  Route::get('/cash-in-create', [ListController::class, 'cashInCreate'])->name('cash-in-create');
  Route::post('/cash-in-store', [ListController::class, 'cashInStore'])->name('cash-in-store');
});

//ajax requests
Route::post('co-status-update', [ListController::class, 'coStatusUpdate']);
Route::get('/delete-cashout/{id}', [ListController::class, 'deleteCashout']);
Route::post('ci-status-update', [ListController::class, 'ciStatusUpdate']);
Route::get('/delete-cashin/{id}', [ListController::class, 'deleteCashin']);
