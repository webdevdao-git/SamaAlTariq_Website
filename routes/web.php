<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Portal\FileController;
use App\Http\Controllers\Portal\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/projects', [PageController::class, 'projects'])->name('projects');

// Throttled per IP because it writes to the database and sends mail — the two
// things a bot can most usefully abuse.
Route::post('/enquiries', [EnquiryController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('enquiries.store');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:10,15');

    // Staff entrance. Same POST handler and the same throttle bucket, so using
    // two doors does not double the number of attempts an attacker gets.
    Route::get('/admin/login', [SessionController::class, 'createAdmin'])->name('admin.login');
    Route::post('/admin/login', [SessionController::class, 'store'])->middleware('throttle:10,15');

    /*
     * Password reset, matching the forgot-password / reset-password pair in the
     * Supabase app. Throttled hard: the request form emails a real person and
     * accepts any address, so it is the most abusable endpoint on the site.
     */
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:5,15')->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('throttle:5,15')->name('password.store');
});

Route::post('/logout', [SessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Client portal
|--------------------------------------------------------------------------
| Every route here is behind `auth`. What each user can actually see is
| decided by ProjectPolicy and Project::visibleTo() — never by the route.
*/

Route::middleware('auth')->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', [ProjectController::class, 'overview'])->name('dashboard');
    Route::get('/images', [ProjectController::class, 'images'])->name('images');
    Route::get('/documents', [ProjectController::class, 'documents'])->name('documents');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // `where` allows slashes so the whole storage path arrives as one segment.
    Route::get('/files/{path}', [FileController::class, 'show'])
        ->where('path', '.*')
        ->name('files.show');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
| `can:viewAny,App\Models\User` gates the whole group through UserPolicy, so
| the admin check lives in one place rather than in every controller.
*/

Route::middleware(['auth', 'can:viewAny,App\Models\User'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects');
        Route::post('/projects', [AdminProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}/edit', [AdminProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [AdminProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');

        Route::get('/images', [MediaController::class, 'images'])->name('images');
        Route::post('/images', [MediaController::class, 'storeImages'])->name('images.store');
        Route::delete('/images/{image}', [MediaController::class, 'destroyImage'])->name('images.destroy');

        Route::get('/reports', [MediaController::class, 'reports'])->name('reports');
        Route::post('/reports', [MediaController::class, 'storeReports'])->name('reports.store');
        Route::delete('/reports/{document}', [MediaController::class, 'destroyReport'])->name('reports.destroy');

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::get('/clients', [SettingsController::class, 'clients'])->name('clients');
        Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');

        Route::post('/clients', [SettingsController::class, 'storeClient'])->name('clients.store');
        Route::put('/clients/{client}/access', [SettingsController::class, 'updateAccess'])->name('clients.access');
        Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
    });
