<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//autentication
Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');


Route::get('/Room', 'RoomController@show');
Route::get('/Room/{id}', 'RoomController@detail');
Route::get('/Type_Room', 'TypeRoomController@show');
Route::get('/Type_Room/{id}', 'TypeRoomController@detail');
Route::get('/Detail_Booking/{id}', 'Detail_BookingController@detail');


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('/login_check', 'UserController@getAuthenticatedUser');

    Route::group(['middleware' => ['api.admin']], function () {

        Route::post('/Room', 'RoomController@store');
        Route::put('/Room/{id}', 'RoomController@update');
        Route::delete('/Room/{id}', 'RoomController@delete');

        Route::post('/Type_Room', 'TypeRoomController@store');
        Route::post('/Type_Room/UploadRoomImage/{id}', 'TypeRoomController@upload_room_image');
        Route::put('/Type_Room/{id}', 'TypeRoomController@update');
        Route::delete('/Type_Room/{id}', 'TypeRoomController@delete');

        Route::delete('/Booking/{id}', 'BookingController@delete');

        Route::post('/Detail_Booking', 'Detail_BookingController@store');
        Route::get('/Detail_Booking', 'Detail_BookingController@show');
        Route::delete('/Detail_Booking/{id}', 'Detail_BookingController@delete');
    });

    Route::group(['middleware' => ['api.receptionist']], function () {

        Route::post('/Booking', 'BookingController@store');
        Route::get('/Booking', 'BookingController@show');
        Route::get('/Booking/{id}', 'BookingController@detail');
        Route::put('/Booking/{id}', 'BookingController@update');
        Route::put('/Detail_Booking/{id}', 'Detail_BookingController@update');
        Route::delete('/Booking/{id}', 'BookingController@delete');

        //filtering 
        Route::get('/Booking/filter/date/{date}', 'BookingController@filterByCheckIn');
        Route::get('/Booking/filter/name/{name}', 'BookingController@filterByName');
    });
});
