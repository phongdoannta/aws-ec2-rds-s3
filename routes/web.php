<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AWSController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['as' => 'aws.', 'prefix' => 'aws'], function () {
    Route::get('list', [AWSController::class, 'index'])->name('list');
    Route::post('s3-upload', [AWSController::class, 'uploadFileS3'])->name('upload');
    Route::post('s3-delete', [AWSController::class, 'deleteFileS3'])->name('delete');
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
