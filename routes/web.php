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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/inscan/v1/'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->get('check-token', 'AuthController@checkToken');

    $router->group(['middleware'=>'logged'], function () use ($router) {
        $router->post('logout', 'AuthController@logout');
        $router->post('generate-sifat', 'GenerateController@generate');
        $router->post('reset-sifat', 'GenerateController@reset');
    });
});