<?php

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
