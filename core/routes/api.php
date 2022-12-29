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

Route::prefix('v1')->group(function (){
    Route::post('signup',[ApiController::class,'signup']);
    Route::post('login',[ApiController::class,'login']);
    Route::post('user_details',[ApiController::class,'userDetails']);
    Route::post('update_user',[ApiController::class,'updateUser']);
    Route::post('delete_user',[ApiController::class,'deleteUser']);

    Route::post('student_list',[ApiController::class,'studentList']);
    Route::post('teacher_list',[ApiController::class,'teacherList']);
    Route::post('approve_user',[ApiController::class,'approveUser']);
    Route::post('assign_teacher',[ApiController::class,'assignTeacher']);

    Route::post('notification_count',[ApiController::class,'notificationCount']);
    Route::post('notification_list',[ApiController::class,'notifyList']);
});
