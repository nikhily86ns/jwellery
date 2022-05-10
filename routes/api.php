<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\PurchaseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//----------------------- Admin Routes Starts ---------------------//

// User Routes

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/users/{id}', [UserController::class, 'users']);

// Branch Routes

Route::post('/create-branch', [BranchController::class, 'create']);
Route::get('/branch', [BranchController::class, 'branch']);

// Product Routes

Route::post('/add-product', [ProductController::class, 'addProduct']);
Route::get('/product-list/{id}', [ProductController::class, 'productList']);

// Village Routes

Route::post('/add-village', [VillageController::class, 'addVillage']);
Route::get('/village', [VillageController::class, 'village']);

// Rates Routes

Route::post('/add-rates', [RateController::class, 'addRates']);
Route::get('/rates', [RateController::class, 'rates']);

//-------------------------- Admin Routes Ends--------------------------//


//------------------------- User Routes Starts--------------------------//

// Bill Routes

Route::post('/add-gst-bill',  [BillController::class, 'addGstBill']);
Route::get('/sales-gst/{id}',  [BillController::class, 'salesGst']);

Route::post('/add-est-bill',  [BillController::class, 'addEstBill']);
Route::get('/sales-est/{id}',  [BillController::class, 'salesEst']);

// Purchase ROutes

Route::post('/add-purchase', [PurchaseController::class, 'addPurchase']);
Route::get('/purchase/{id}', [PurchaseController::class, 'purchase']);



//--------------------------- User Routes Ends-----------------------------//