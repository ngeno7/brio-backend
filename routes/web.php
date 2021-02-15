<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\GlobalKPIController;


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

$router->get('/global-kpis', 'GlobalKPIController@index');
$router->get('/global-kpis/{slug}', 'GlobalKPIController@single');
$router->post('/global-kpis', 'GlobalKPIController@store');
$router->put('/global-kpis/{id}', 'GlobalKPIController@update');

$router->get('/kpi-items/{slug}', 'KPIItemController@index');
$router->post('/kpi-items', 'KPIItemController@store');
$router->put('/kpi-items/{slug}', 'KPIItemController@update');

$router->get('/clients', 'ClientController@index');
$router->post('/clients', 'ClientController@store');
$router->post('/clients/{slug}', 'ClientController@update');

$router->get('/client-kpis-all/{client}', 'ClientKPIController@index');
$router->get('/client-kpis/{client}/{kpi}', 'ClientKPIController@clientKPI');
$router->post('/client-kpis/{clientSlug}/{kpiSlug}', 'ClientKPIController@store');
