<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('products/user_update', [ProductController::class, 'updateUserProduct'])->name('products.user_update');
Route::post('products/fetch_remote', [ProductController::class, 'productInfoFromEAN'])->name('products.fetch_remote');
Route::post('products/search_name', [ProductController::class, 'searchByName'])->name('products.search_name');
Route::post('products/user_store', [ProductController::class, 'storeUserProduct'])->name('products.user_store');
Route::delete('products/user_delete', [ProductController::class, 'deleteUserProduct'])->name('products.user_delete');
Route::resource('products', ProductController::class);

Route::resource('favorites', FavoriteController::class);

require __DIR__.'/auth.php';
