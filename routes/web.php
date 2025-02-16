<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::middleware(['auth'])->group(function () {
    // Admin-specific routes
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('books', BookController::class, [
            'except' => ['show', 'borrow', 'return'],
        ]);
    });

    // User-specific routes
    Route::middleware(['role:user'])->group(function () {
        Route::post('/books/{book}/borrow', [BookController::class, 'borrow'])
            ->name('books.borrow');

        Route::post('/books/{book}/return', [BookController::class, 'return'])
            ->name('books.return');
    });

    // Common routes accessible to both roles
    Route::get('/books', [BookController::class, 'index'])
        ->name('books.index');
    
    Route::get('/books/export-pdf', [BookController::class, 'exportPdf'])
        ->name('books.export-pdf');
});
