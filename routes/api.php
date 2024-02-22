<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/invoice', [InvoiceController::class, 'create']);
Route::get('/invoices', [InvoiceController::class, 'get']);
Route::delete('/invoice/{id}', [InvoiceController::class, 'delete']);

Route::post('/tag', [TagController::class, 'create']);
Route::get('/tag', [TagController::class, 'get']);
Route::delete('/tag/{id}', [TagController::class, 'delete']);

Route::post('/syncTags', [InvoiceController::class, 'syncTags']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
