<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\StartController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [StartController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/wls', [WlController::class, 'index'])->name('wls.index');
    Route::match(['put', 'post'],'/wls', [WlController::class, 'save'])->name('wls.save');
    Route::post('/wls/{wl}/delete', [WlController::class, 'destroy'])->name('wls.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users', [UserController::class, 'save'])->name('users.save');
    Route::post('/users/{user}/delete', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::match(['put', 'post'], '/categories', [CategoryController::class, 'save'])->name('categories.save');
    Route::post('/categories/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::match(['put', 'post'], '/tags', [TagController::class, 'save'])->name('tags.save');
    Route::post('/tags/{tag}/delete', [TagController::class, 'destroy'])->name('tags.destroy');

    Route::resource('receipts', ReceiptController::class);
    Route::post('/ajax_filter_receipts', [ReceiptController::class, 'filter']);
    Route::post('/ajax_sorting_receipts', [ReceiptController::class, 'sorting']);

    Route::resource('articles', ArticleController::class);

});

require __DIR__.'/auth.php';
