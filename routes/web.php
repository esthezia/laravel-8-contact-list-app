<?php

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

// the welcome page
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);

// public contact listing
Route::get('/contacts', [\App\Http\Controllers\HomeController::class, 'contacts'])->name('contacts');

// login and logout
Route::match(['get', 'post'], 'login', [\App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::get('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

// admin area
Route::prefix('admin')->middleware('auth')->group(function () {
    // admin dashboard
    Route::get('', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin');

    // contact listing
    Route::get('/contacts', [\App\Http\Controllers\AdminController::class, 'contacts'])->name('admin.contacts');

    // create/edit contact
    Route::match(['get', 'post'], '/create/{contact?}', [\App\Http\Controllers\AdminController::class, 'create'])->name('admin.create');

    // edit deleted contact and restore
    Route::match(['get', 'post'], '/edit-restore/{deletedContact}', [\App\Http\Controllers\AdminController::class, 'editRestore'])->name('admin.edit-restore');

    // delete contact
    Route::get('/delete/{id}', [\App\Http\Controllers\AdminController::class, 'delete'])->name('admin.delete');

    // restore deleted contact
    Route::get('/restore/{id}', [\App\Http\Controllers\AdminController::class, 'restore'])->name('admin.restore');

    // delete a contact forever
    Route::get('/delete-forever/{id}', [\App\Http\Controllers\AdminController::class, 'deleteForever'])->name('admin.delete-forever');

    // deleted contacts listing
    Route::get('/deleted', [\App\Http\Controllers\AdminController::class, 'deleted'])->name('admin.deleted');

    // set contacts' visibility,
    // i.e. whether they are publicly accessible or not.
    // note: this will only be called through ajax.
    Route::post('/set-visibility', [\App\Http\Controllers\AdminController::class, 'setVisibility'])->name('admin.set-visibility');
});
