<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Pelanggan\ProductController as PelangganProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirect /dashboard ke route yang sesuai dengan role
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('pelanggan.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- Admin routes ---
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        Route::resource('products', AdminProductController::class)
            ->except(['show']);
    });

// --- Pelanggan routes ---
Route::middleware(['auth', 'verified', 'role:pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('pelanggan.dashboard');
        })->name('dashboard');

        Route::get('/products', [PelangganProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [PelangganProductController::class, 'show'])->name('products.show');
    });

// --- Profile (all authenticated users) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
