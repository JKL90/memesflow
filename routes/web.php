<?php

use App\Http\Controllers\MemesController;
use App\Http\Controllers\PageController;
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

// Route::get('/', function () {
//     return view('memes');
// });

Route::get('/', [MemesController::class, 'index'])->name('memes');
Route::get('/memes/create', [MemesController::class, 'create'])->name('memes.create');
Route::post('/memes', [MemesController::class, 'store'])->name('memes.store');

Route::get('/about', [PageController::class, 'about'])->name('pages.about');
