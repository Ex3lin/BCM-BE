<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ScheduleInvoiceController;
use App\Http\Controllers\SummaryMonthController;
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

Route::post('/invoice', [InvoiceController::class, 'createInvoice']);
Route::get('/invoices', [InvoiceController::class, 'getInvoices']);
Route::patch('/invoice/{invoice}', [InvoiceController::class, 'updateInvoice']);
Route::delete('/invoice/{id}', [InvoiceController::class, 'deleteInvoice']);

Route::get('/summary', [InvoiceController::class, 'getSumOfDates']);

Route::post('/tag', [TagController::class, 'create']);
Route::get('/tags', [TagController::class, 'get']);
Route::delete('/tag/{id}', [TagController::class, 'delete']);

Route::post('/attachTags', [InvoiceController::class, 'attachTags']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
