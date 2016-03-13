<?php

// Web group route, used for serving the Angular-app

Route::group(['middleware' => ['web']], function(){
	
	// SPA is served here, ie React, Angular etc
	Route::get('/', 'PagesController@home');
	
});


// API group route:
Route::group(['prefix' => 'api/v1', 'middleware' => ['api']], function()
{
	 // Authentication JWT stuff
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
    Route::get('authenticate/logout', 'AuthenticateController@logout');

    // Custom stuff
    Route::resource('users', 'UserController');

     // Password reset
    Route::post('password/forgot', 'PasswordController@forgotPassword');
    Route::post('password/setnew', 'PasswordController@setNewPassword');
});
