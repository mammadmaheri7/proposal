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

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'API\UserController@details');
    Route::post('define_supervisor','API\SupervisorController@define_supervisor_for_student');
    Route::post('get_professors_information','API\DepartmentHeadController@get_professor_information');
    Route::post('get_proposals_information','API\DepartmentHeadController@get_proposals_information');
    Route::post('choose_judge','API\DepartmentHeadController@choose_judge');
    Route::post('modify_user','API\UserController@modify_user');
    Route::get('users','API\UserController@index');

    Route::resource('major','MajorController');
    Route::resource('field','FieldController');

});
