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

$router->group(['prefix' => 'adminstrativo'], function () use ($router) {
    $router->options('/cadastroPropietario', ['middleware' => ['CorsDomain', 'AuthToken' , 'Timezone']]);
    $router->post(
        '/cadastroPropietario',
        ['uses' => 'Propietario\PropietarioController@cadastraPropietario']
    );

    $router->options('/login', ['middleware' => ['CorsDomain', 'AuthToken' , 'Timezone']]);
    $router->post(
        '/cadastroPropietario',
        ['uses' => 'Propietario\PropietarioController@login']
    );
});



