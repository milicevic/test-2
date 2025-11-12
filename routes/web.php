<?php

use App\Http\Controllers\DataImportController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::resource( 'users', UserController::class);
    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permission}/assign', [PermissionController::class, 'assignUsers'])->name('permissions.assign');
    Route::post('permissions/{permission}/assign', [PermissionController::class, 'saveUsersAssignment'])->name('permissions.save.assign');
    Route::get('data-import', [DataImportController::class, 'create'])->name('data.import.create');
    Route::post('data-import/upload', [DataImportController::class, 'upload'])->name('data.import.upload');

});

