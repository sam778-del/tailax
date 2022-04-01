<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BranchController;

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

require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware(["auth"]);

// Service Area
Route::resource('services', ServiceController::class)->middleware(["auth"]);

// Branch Area
Route::resource('branches', BranchController::class)->middleware(['auth']);
Route::get("/get-branches", [BranchController::class, "datatables"])
        ->middleware('auth')
        ->name("branches.datatables");
