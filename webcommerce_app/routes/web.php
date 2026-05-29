<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerReportController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\StoreSettingController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;



Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/checkout/apply-voucher', [CheckoutController::class, 'apply'])->name('checkout.apply-voucher');
Route::post('/checkout/remove-voucher', [CheckoutController::class, 'remove'])->name('checkout.remove-voucher');

Route::get('/confirmation', [ConfirmationController::class, 'index'])->name('confirmation.index');
Route::post('/confirmation', [ConfirmationController::class, 'search'])->name('confirmation.search');
Route::get('/confirmation/{code}', [ConfirmationController::class, 'confirmation'])->name('confirmation.upload-proof');
Route::post('/confirmation/{code}', [ConfirmationController::class, 'uploadProof'])->name('confirmation.upload-proof-process');
Route::get('/confirmation/invoice/{code}', [ConfirmationController::class, 'invoice'])->name('confirmation.invoice');

Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/tracking', [TrackingController::class, 'search'])->name('tracking.process');
Route::get('/tracking/{code}', [TrackingController::class, 'show'])->name('tracking.show');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('dashboard');

    Route::get('menu-categories/list', [MenuCategoryController::class, 'list'])->name('menu-categories.list');
    Route::get('menu-categories/{slug}/edit', [MenuCategoryController::class, 'edit'])->name('menu-categories.edit');
    Route::put('menu-categories/{slug}', [MenuCategoryController::class, 'update'])->name('menu-categories.update');
    Route::delete('menu-categories/{slug}', [MenuCategoryController::class, 'destroy'])->name('menu-categories.destroy');
    Route::resource('menu-categories', MenuCategoryController::class)->except(['edit', 'update', 'destroy']);

    Route::get('menu/list', [MenuController::class, 'list'])->name('menu.list');
    Route::get('menu/{slug}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('menu/{slug}', [MenuController::class, 'update'])->name('menu.update');
    Route::resource('menu', MenuController::class)->except(['edit', 'update']);

    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/list', [CustomerController::class, 'list'])->name('customers.list');
    Route::get('customers/{id}', [CustomerController::class, 'show'])->name('customers.show');

    Route::get('vouchers/list', [VoucherController::class, 'list'])->name('vouchers.list');
    Route::resource('vouchers', VoucherController::class);

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/list', [OrderController::class, 'list'])->name('orders.list');
    Route::get('orders/{code}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{encrypted}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{id}/approve-payment', [OrderController::class, 'approvePayment'])->name('orders.approve-payment');

    Route::get('sales-reports', [SalesReportController::class, 'index'])->name('reports.sales');
    Route::get('sales-reports/list', [SalesReportController::class, 'list'])->name('reports.sales.list');
    Route::get('sales-reports/export', [SalesReportController::class, 'export'])->name('reports.sales.export');
    Route::get('sales-reports/{code}/detail', [SalesReportController::class, 'detail'])->name('reports.sales.detail');
    Route::get('sales-reports/{code}/export-pdf', [SalesReportController::class, 'exportPDF'])->name('reports.sales.detail.export-pdf');


    Route::get('customer-reports', [CustomerReportController::class, 'index'])->name('reports.customer');
    Route::get('customer-reports/list', [CustomerReportController::class, 'list'])->name('reports.customer.list');
    Route::get('customer-reports/export', [CustomerReportController::class, 'export'])->name('reports.customer.export');

    Route::get('store-settings', [StoreSettingController::class, 'index'])->name('store-settings.index');
    Route::put('store-settings', [StoreSettingController::class, 'update'])->name('store-settings.update');

    Route::get('change-password', [ChangePasswordController::class, 'index'])->name('change-password');
    Route::post('change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
