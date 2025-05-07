<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/login', function () {
//     return view('login');
// })->name('login');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login/post', [LoginController::class, 'loginEmail'])->name('loginEmail');

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [FileController::class, 'showDashboard'])->name('dashboard');
    Route::get('/documents/{folder?}', [FileController::class, 'showDocuments'])->name('documents')->where('folder', '.*');
    Route::get('/media/{folder?}', [FileController::class, 'showMedia'])->name('media')->where('folder', '.*');
    Route::get('/archive/{folder?}', [FileController::class, 'showArchive'])->name('archive')->where('folder', '.*');
    Route::get('/others/{folder?}', [FileController::class, 'showOthers'])->name('others')->where('folder', '.*');

    Route::post('/update-file-name', [FileController::class, 'updateFileName'])->name('update-file-name');

    Route::get('/trash/{folder?}', [FileController::class, 'showTrash'])->name('trash')->where('folder', '.*');

    Route::get('/folders/download', [FileController::class, 'downloadFolder'])->name('folders.download');

    Route::post('/documents/upload', [FileController::class, 'upload'])->name('upload');
    Route::post('/documents/upload/check', [FileController::class, 'checkIfExists'])->name('upload.check');

    Route::post('/documents/create-folder', [FileController::class, 'createFolder'])->name('createFolder');

    Route::post('/move-to-archive', [FileController::class, 'moveToArchive'])->name('moveToArchive');
    Route::post('/move-to-trash', [FileController::class, 'moveToTrash'])->name('moveToTrash');
    Route::post('/restore-files', [FileController::class, 'restoreFiles'])->name('restoreFiles');
    Route::post('/delete-files', [FileController::class, 'deleteFile'])->name('deleteFile');
});

