<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('mail_notification',[ApiController::class,'mail_notification']);
Route::post('db_notification',[ApiController::class,'db_notification']);
Route::post('notification_count',[ApiController::class,'notification_count']);
Route::post('notification_list',[ApiController::class,'notification_list']);

