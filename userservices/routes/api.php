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

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::post('get_user', [ApiController::class, 'get_user']);
    Route::post('update_user',[ApiController::class,'update_user']);
    Route::post('delete_user',[ApiController::class,'delete_user']);

    Route::post('student_list',[ApiController::class,'student_list']);
    Route::post('teacher_list',[ApiController::class,'teacher_list']);
    Route::post('approve_user',[ApiController::class,'approve_user']);
    Route::post('assign_teacher',[ApiController::class,'assign_teacher']);
    Route::post('verify_user',[ApiController::class,'verify_user']);
});
