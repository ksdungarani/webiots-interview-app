<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\ProductController;


Route::get('/', [ShopifyController::class, 'index'])
    ->middleware(['verify.shopify', 'billable'])
    ->name('home');

Route::post('/products/store', [ProductController::class, 'store'])
    ->middleware(['verify.shopify', 'billable'])
    ->name('products.store');

// Route::get('/', [ShopifyController::class, 'afterAuth'])->name('after-auth');
// Route::get('/choose-plan', [BillingController::class, 'showPlans'])->name('choose.plan'); // plan selection
// Route::post('/subscribe/{plan}', [BillingController::class, 'subscribe'])->name('subscribe.plan'); // create charge
// Route::get('/billing/callback', [BillingController::class, 'callback'])->name('billing.callback'); // after approval




