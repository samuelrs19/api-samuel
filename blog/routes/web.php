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

$router->group(['prefix' => '/api/v1'], function () use ($router) {

    $router->post('/criarPlano', 'PagSeguroController@criarPlano');
    $router->get('/session', 'PagSeguroController@getSession');
    $router->post('/adesao', 'PagSeguroController@aderirPlano');
    $router->post('/webhook', 'WebHook@recebePost');
});

$router->get('/adesao', function () {
    return view("adesao");
});
$router->get('/criarplano', function () {
    return view("criarplano");
});
