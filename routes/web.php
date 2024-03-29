<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix'=>'api/v1/'], function () use ($router){

    $router->post('users', ['uses' => 'UserController@create']);
    $router->post('login',['uses' => 'AuthController@postLogin']);

});
$router->group(['prefix'=>'api/v1/', 'middleware' => 'auth:api'], function () use ($router){
    // users
    $router->get('users', ['uses'=>'UserController@showAllUsers']);
    $router->get('users/{id}', ['uses' => 'UserController@showOneUser']);
    $router->delete('users/{id}', ['uses' => 'UserController@delete']);
    $router->put('users/{id}', ['uses' => 'UserController@update']);
    // jobs
    $router->post('jobs', ['uses' => 'JobsController@create']);
    $router->put('jobs/{id}', ['uses' => 'JobsController@update']);
    $router->delete('jobs/{id}', ['uses' => 'JobsController@delete']);
    $router->get('jobs/{id}', ['uses' => 'JobsController@ShowOneJob']);
    $router->get('jobs/{id}/applications', ['uses' => 'JobsController@showJobWithApplications']);
    $router->get('jobs', ['uses'=>'JobsController@showAllJobs']);
    // applications
    $router->post('applications', ['uses' => 'ApplicationController@create']);
    $router->put('applications/{id}', ['uses' => 'ApplicationController@update']);
    $router->delete('applications/{id}', ['uses' => 'ApplicationController@delete']);
    $router->get('applications/{id}', ['uses' => 'ApplicationController@ShowOneApplication']);
    $router->get('applications', ['uses'=>'ApplicationController@showAllApplications']);
});
