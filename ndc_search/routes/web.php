<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NDCController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('search.index');
});

Route::get('/dashboard', function () {
    return redirect()->route('search.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/search', [NDCController::class, 'index'])->name('search.index');
    Route::post('/search', [NDCController::class, 'search'])->name('search.submit');
    Route::get('/export', [NDCController::class, 'export'])->name('search.export');
    Route::delete('/ndc/{id}', [NDCController::class, 'delete'])->name('ndc.delete');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

require __DIR__.'/auth.php';
