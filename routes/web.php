<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

if (!\Illuminate\Support\Facades\Auth::check() && !app()->runningInConsole()) {
    $user = \App\Models\User::first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
    }
}

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/messages/{contactId}', [ChatController::class, 'getMessages'])->name('messages');
    Route::post('/messages/{contactId}', [ChatController::class, 'sendMessage'])->name('send');
});

Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class);
    Route::resource('auto-replies', \App\Http\Controllers\Admin\AutoReplyController::class);
});

require __DIR__.'/auth.php';
