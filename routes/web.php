<?php

use App\Http\Controllers\DataImportController;
use App\Http\Controllers\ImportAuditController;
use App\Http\Controllers\ImportLogController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::resource( 'users', UserController::class);
    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permission}/assign', [PermissionController::class, 'assignUsers'])->name('permissions.assign');
    Route::post('permissions/{permission}/assign', [PermissionController::class, 'saveUsersAssignment'])
    ->name('permissions.save.assign');
    Route::get('imports', [DataImportController::class, 'create'])->name('import.create');
    Route::post('imports/upload', [DataImportController::class, 'upload'])->name('import.upload');

    //list of import data
    Route::get('imports/{importType}/{fileKey}', [DataImportController::class, 'index'])->name('import.index');
    //list of log base on rule for create or update
    Route::get('/imports/{importType}/{fileKey}/logs/{rowNumber}', [ImportAuditController::class, 'getLogs'])->name('import.logs');
    Route::delete('/imports/{importType}/{id}', [DataImportController::class, 'delete'])->name('import.destroy');

    //log
    Route::get('/imports/logs', [ImportLogController::class, 'index'])->name('import.logs');
});

