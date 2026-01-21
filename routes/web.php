<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenyetoranHarianController;
use App\Http\Controllers\PosPenyetoranController;
use App\Http\Controllers\PeternakController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\WelcomeController;

// Landing page
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public Article Routes (No Auth Required)
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('article.show');

// Auth routes
require __DIR__.'/auth.php';

// Login admin shortcut (hanya untuk development, hapus di production!)
Route::get('/login-as-admin', function() {
    $admin = \App\Models\User::where('role','admin')->first();
    if ($admin) {
        Auth::login($admin);
        return redirect()->route('dashboard');
    }
    return redirect()->route('login')->with('error', 'Admin user not found');
})->name('login.admin');

// ========== PUBLIC ROUTES (Tanpa Auth) ==========
// API untuk modal detail artikel
Route::get('/api/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

// Routes untuk user yang sudah login
Route::middleware('auth')->group(function () {
    
    // Dashboard - Semua role bisa akses
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile - Semua role bisa akses
    Route::prefix('profile')->name('profile.')->group(function(){
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // ========== ADMIN (SUPER ADMIN + ADMIN POS) ==========
    Route::middleware(['admin'])->group(function(){
        
        // Artikel - READ ONLY untuk Admin Pos (HARUS DI ATAS SUPERADMIN)
        Route::get('/articles/list', [ArticleController::class, 'listForAdminPos'])->name('articles.list');

        // PENYETORAN HARIAN
        Route::middleware(['pos.access'])->group(function(){
            Route::prefix('penyetoran')->name('penyetoran.')->group(function(){
                Route::get('/', [PenyetoranHarianController::class, 'index'])->name('index');
                Route::get('/{pos}', [PenyetoranHarianController::class, 'show'])->name('show');
                Route::post('/update-cell', [PenyetoranHarianController::class, 'updateCell'])->name('updateCell');
                Route::delete('/{id}', [PenyetoranHarianController::class, 'destroy'])->name('destroy');
                Route::get('/export/excel', [PenyetoranHarianController::class, 'export'])->name('export');
            });
        });

        // POS PENYETORAN
        Route::prefix('pos')->name('pos.')->group(function(){
            Route::get('/', [PosPenyetoranController::class, 'index'])->name('index');
            Route::post('/store-ajax', [PosPenyetoranController::class, 'storeAjax'])->name('storeAjax');
            Route::get('/list', [PosPenyetoranController::class, 'getList'])->name('list');
            Route::put('/{pos}', [PosPenyetoranController::class, 'update'])->name('update');
            Route::delete('/{pos}', [PosPenyetoranController::class, 'destroy'])->name('destroy');
        });

        // PETERNAK
        Route::prefix('peternak')->name('peternak.')->group(function(){
            Route::get('/', [PeternakController::class, 'index'])->name('index');
            Route::post('/store-ajax', [PeternakController::class, 'storeAjax'])->name('storeAjax');
            Route::get('/by-pos/{posId}', [PeternakController::class, 'getByPos'])->name('byPos');
            Route::put('/{peternak}', [PeternakController::class, 'update'])->name('update');
            Route::delete('/{peternak}', [PeternakController::class, 'destroy'])->name('destroy');
        });
    });

    // ========== SUPER ADMIN ONLY ==========
    Route::middleware(['superadmin'])->group(function(){
        
        // CRUD User
        Route::prefix('users')->name('users.')->group(function(){
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        // CRUD Artikel (Create, Update, Delete)
        Route::prefix('admin/articles')->name('articles.')->group(function(){
            Route::get('/', [ArticleController::class, 'index'])->name('index');
            Route::get('/create', [ArticleController::class, 'create'])->name('create');
            Route::post('/', [ArticleController::class, 'store'])->name('store');
            Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
            Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
            Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
        });
    });
});