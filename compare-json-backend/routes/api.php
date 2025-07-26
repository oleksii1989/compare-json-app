<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompareJsonController;

Route::post('/payload1', [CompareJsonController::class, 'storePayload1']);
Route::post('/payload2', [CompareJsonController::class, 'storePayload2']);
Route::get('/diff', [CompareJsonController::class, 'getDiff']);
