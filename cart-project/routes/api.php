<?php

use App\Modules\Cart\Connectors\Adapters\Primary\GetCartController;
use App\Modules\Cart\Connectors\Adapters\Primary\RemoveProductFromCartController;
use Illuminate\Support\Facades\Route;
use App\Modules\Cart\Connectors\Adapters\Primary\SaveProductToCartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/v1/cart/add', [SaveProductToCartController::class, 'execute']);
Route::get('/v1/cart/{user_id}', [GetCartController::class, 'execute']);
Route::delete('/v1/cart/remove/{user_id}/{product_id}', [RemoveProductFromCartController::class, 'execute']);
