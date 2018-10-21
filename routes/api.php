<?php

use Illuminate\Http\Request;

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::post('social_login/{provider}','API\AuthController@socialLogin');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/get-auth-user', 'API\UserController@getCurrentUser');
    Route::post('/logout', 'API\AuthController@logout');

});