<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/key', function () use ($router) {
    return \Illuminate\Support\Str::random(64);
});

$router->get('/generate', 'AuthController@generate');

$router->group([
    'prefix' => 'api'
], function () use ($router){
    $router->get('/generate', 'AuthController@generate');
    $router->post('login', 'AuthController@login');
    $router->post('authorized', 'AuthController@authorized');
    $router->post('me', 'AuthController@me');
    $router->post('logout', 'AuthController@logout');

    $router->group([
        'prefix' => 'page'
    ], function () use ($router){
        $router->get('/mandor', 'PageController@mandor'); // Bebug

        $router->post('/mandor', 'PageController@mandor');
        $router->post('/anggota', 'PageController@anggota');
    });

    $router->group([
        'prefix' => 'raw'
    ], function () use ($router){
        $router->get('/mandor', 'RawController@mandor'); // Bebug

        $router->post('/mandor', 'RawController@mandor');
    });

    $router->group([
        'prefix' => 'mandor'
    ], function () use ($router){
        $router->get('/', 'MandorController@index');
        $router->get('/form', 'MandorController@form');
        $router->post('/form', 'MandorController@store');
        $router->put('/form', 'MandorController@update');
        $router->delete('/form', 'MandorController@destroy');
    });

    $router->group([
        'prefix' => 'anggota'
    ], function () use ($router){
        $router->get('/', 'AnggotaController@index');
        $router->get('/form', 'AnggotaController@form');
        $router->post('/form', 'AnggotaController@store');
        $router->put('/form', 'AnggotaController@update');
        $router->delete('/form', 'AnggotaController@destroy');
    });
});
