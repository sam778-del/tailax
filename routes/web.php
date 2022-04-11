<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductionStageController;
use App\Http\Controllers\FabricSizeController;

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

// Branch Area
Route::resource('branches', BranchController::class)->middleware(['auth']);
Route::get("/get-branches", [BranchController::class, "datatables"])
        ->middleware('auth')
        ->name("branches.datatables");

// Currency Area
Route::resource('currencies', CurrencyController::class)->middleware('auth');
Route::get('/get-currencies', [CurrencyController::class, "datatables"])
        ->middleware('auth')
        ->name('currencies.datatables');
Route::patch('/currency-status/{currency_id}', [CurrencyController::class, 'changeCurrencyStatus'])
        ->middleware('auth')
        ->name('currencies.default');

// Customer Area
Route::resource('customers', CustomerController::class)->middleware('auth');
Route::get('get-customers', [CustomerController::class, 'datatables'])
        ->middleware('auth')
        ->name('customers.datatables');

// Production Stage Area
Route::resource('production_stages', ProductionStageController::class)->middleware('auth');
Route::get('get-production-stage', [ProductionStageController::class, 'datatables'])
        ->middleware('auth')
        ->name('production.stage.datatables');

// Fabric Size Area
Route::resource('fabric_sizes', FabricSizeController::class)->middleware('auth');
Route::get('get-fabric-size', [FabricSizeController::class, 'datatables'])
        ->middleware('auth')
        ->name('fabric.size.datatables');
