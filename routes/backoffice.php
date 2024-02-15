<?php

use App\Http\Controllers\Backoffice\DashboardController;
use App\Http\Controllers\Backoffice\DiscountController;
use App\Http\Controllers\Backoffice\ProductController;
use App\Http\Controllers\Backoffice\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/utenti', [UserController::class, 'getView'])->name('users');
Route::get('/sconti', [DiscountController::class, 'getView'])->name('discounts');
Route::get('/prodotti', [ProductController::class, 'getView'])->name('products');
Route::get('/prodotti/nuovo', [ProductController::class, 'getNewView'])->name('products.new');

Route::prefix('api')->name('api.')->group(function () {
    $elements = [
        'user' => UserController::class,
        'discount' => DiscountController::class,
        'product' => ProductController::class,
    ];

    foreach ($elements as $key => $controller) {
        Route::post($key.'s', [$controller, 'apiList'])->name($key);
        Route::post($key.'/patch-or-create', [$controller, 'apiPatchOrCreate'])->name($key.'.patch-or-create');
        Route::post($key.'/delete', [$controller, 'apiDelete'])->name($key.'.delete');
        Route::post($key.'/autocomplete', [$controller, 'apiAutocompleSearch'])->name($key.'.autocomplete');
        Route::post($key.'/search', [$controller, 'apiSearch'])->name($key.'.search');
    }

    // Route::post('users', [UserController::class, 'apiList'])->name('user');
    // Route::post('user/patch-or-create', [UserController::class, 'apiPatchOrCreate'])->name('user.patch-or-create');
    // Route::post('user/delete', [UserController::class, 'apiDelete'])->name('user.delete');
    // Route::post('user/autocomplete', [UserController::class, 'apiAutocompleSearch'])->name('user.autocomplete');
    // Route::post('user/search', [UserController::class, 'apiSearch'])->name('user.search');
});
