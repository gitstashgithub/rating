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

    return Redirect::route('lesson', [1]);
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
//    Route::get('lesson', [
//        'as' => 'lesson',
//        'uses' => 'LessonController@all'
//    ]);
    Route::get('lesson/{id}/edit', [
        'as' => 'lesson.edit',
        'middleware' => 'auth',
        'uses' => 'LessonController@edit'
    ]);
    Route::get('lecture/{lectureId}/lesson/create', [
        'as' => 'lesson.create',
        'middleware' => 'auth',
        'uses' => 'LessonController@create'
    ]);

    Route::post('lesson', [
        'as' => 'lesson.store',
        'middleware' => 'auth',
        'uses' => 'LessonController@store'
    ]);
    Route::put('lesson/{id}', [
        'as' => 'lesson.update',
        'middleware' => 'auth',
        'uses' => 'LessonController@update'
    ]);

    Route::get('lesson/{id}', [
        'uses' => 'LessonController@show'
    ]);
    Route::get('lecture/{id}/lesson/', [
        'as' => 'lesson',
        'uses' => 'LessonController@all'
    ]);
    Route::post('lesson/{id}/enable/', [
        'uses' => 'LessonController@enable'
    ]);
    Route::get('lesson/{id}/export/', [
        'uses' => 'LessonController@export'
    ]);


    Route::get('rating/{id}/users', [
        'uses' => 'RatingController@getUsersResults'
    ]);
    Route::get('rating/{id}', [
        'uses' => 'RatingController@getResult'
    ]);
    Route::post('rating/{id}/toggleDelete', [
        'uses' => 'RatingController@toggleDelete'
    ]);
    Route::post('rating', [
        'uses' => 'RatingController@setRate'
    ]);
    Route::post('bookmark', [
        'uses' => 'RatingController@setBookmark'
    ]);
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('lecture', [
        'as' => 'lecture',
        'uses' => 'LectureController@all'
    ]);
    Route::get('lecture/{id}', [
        'uses' => 'LectureController@show'
    ]);
    Route::post('lecture', [
        'uses' => 'LectureController@store'
    ]);
    Route::put('lecture', [
        'uses' => 'LectureController@update'
    ]);
});