<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/', [
    'uses' => 'LessonController@all'
]);*/

Route::get('/', function () {
    return Redirect::route('lesson');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('lesson', [
        'as' => 'lesson',
        'uses' => 'LessonController@all'
    ]);
    Route::get('lesson/{id}', [
        'uses' => 'LessonController@show'
    ]);
    Route::get('rate/{id}', [
        'uses' => 'RateController@getResult'
    ]);
    Route::post('rate', [
        'uses' => 'RateController@setRate'
    ]);
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
