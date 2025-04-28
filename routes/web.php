<?php

use App\Http\Controllers\FileController;
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

Route::get('/login', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard.main');
})->name('dashboard');

// Route::get('/documents', function () {
//     return view('dashboard.documents');
// })->name('documents');

Route::get('/documents/{folder?}', [FileController::class, 'showDocuments'])->name('documents')->where('folder', '.*');
Route::get('/trash/{folder?}', [FileController::class, 'showTrash'])->name('trash')->where('folder', '.*');

Route::get('/folders/download', [FileController::class, 'downloadFolder'])->name('folders.download');

Route::post('/documents/upload', [FileController::class, 'upload'])->name('upload');
Route::post('/documents/upload/check', [FileController::class, 'checkIfExists'])->name('upload.check');

Route::post('/documents/create-folder', [FileController::class, 'createFolder'])->name('createFolder');

Route::post('/move-to-trash', [FileController::class, 'moveToTrash'])->name('moveToTrash');