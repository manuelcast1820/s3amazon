<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ImageController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('image-upload', [ImageController::class, 'index' ])->name('image.index');
Route::get('file-upload', [FileController::class, 'index' ])->name('file.index');
//Route::post('image-upload', [ImageController::class, 'upload' ])->name('image.upload');

Route::post('image-upload', [ ImageController::class, 'imageUploadPost' ])->name('image.upload.post');
Route::post('file-upload', [ FileController::class, 'fileUpload' ])->name('file.upload');

