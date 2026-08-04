<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return 'Cache cleared successfully!';
});

Route::get('/logs', function () {
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        return response('<pre>' . file_get_contents($logPath) . '</pre>');
    }
    return 'No logs found.';
});

Route::get('/setup-admin', function () {
    $user = \App\Models\User::firstOrCreate(
        ['name' => 'admin'],
        [
            'email' => 'admin@otomasi.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123')
        ]
    );
    
    $user->update([
        'password' => \Illuminate\Support\Facades\Hash::make('admin123')
    ]);
    
    return redirect()->route('login')->with('status', 'Akun admin berhasil dibuat/direset! Silakan login.');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/messages/{contactId}', [ChatController::class, 'show'])->name('messages');
    Route::post('/messages/{contactId}', [ChatController::class, 'sendMessage'])->name('send');
});

Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class);
    Route::resource('auto-replies', \App\Http\Controllers\Admin\AutoReplyController::class);
});

require __DIR__.'/auth.php';
