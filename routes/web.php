<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\GlobalKPIController;
use Illuminate\Support\Facades\Mail;

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

$router->post('/client-login', 'UserController@logInClient');
$router->post('/user-login', 'UserController@logInUser');

$router->post('/get-verification-code', 'ClientController@resetPasswordInit');
$router->post('/reset-password', 'ClientController@resetPassword');

$router->get('/test-mail', function() {
    $data = ['email' => 'Ngeno', 'password' => '123456'];
    Mail::send('mail', $data, function($message) {
        $message->to('hillaryng14@gmail.com')->subject('Brio Account');
        $message->from('info@brio.com', 'Brio Team');
    });
});

$router->group(['middleware' => 'auth'], function() use($router) {

    $router->get('/global-kpis', 'GlobalKPIController@index');
    $router->get('/global-kpis/{slug}', 'GlobalKPIController@show');
    $router->get('/global-kpis/{kpi}/{clientSlug}', 'GlobalKPIController@single');
    $router->post('/global-kpis', 'GlobalKPIController@store');
    $router->put('/global-kpis/{id}', 'GlobalKPIController@update');

    $router->get('/kpi-items/{slug}', 'KPIItemController@index');
    $router->post('/kpi-items', 'KPIItemController@store');
    $router->put('/kpi-items/{slug}', 'KPIItemController@update');
    $router->delete('/kpi-items/{slug}', 'KPIItemController@destroy');

    $router->get('/clients', 'ClientController@index');
    $router->get('/clients-recent', 'ClientController@recentClients');
    $router->get('/clients/{slug}', 'ClientController@single');
    $router->post('/clients', 'ClientController@store');
    $router->post('/clients/{slug}', 'ClientController@update');

    $router->get('/global-client-kpis/{clientSlug}', 'GlobalClientKPIController@index');
    $router->get('/global-client-kpis/{clientSlug}/{kpiSlug}', 'GlobalClientKPIController@kpiItems');
    $router->post('/global-client-kpis/{clientSlug}', 'GlobalClientKPIController@storeScore');

    $router->get('/client-kpis-all/{client}', 'ClientKPIController@index');
    $router->get('/client-kpis/{client}/{kpi}', 'ClientKPIController@clientKPI');
    $router->post('/client-kpis/{clientSlug}/{kpiSlug}', 'ClientKPIController@store');
    $router->get('/client-kpis-score/{client}', 'ClientKPIController@score');
    

    $router->post('/client-kpi-item/{kpiSlug}/{clientSlug}', 'ClientKPIItemController@store');
    $router->delete('/client-kpi-item/{id}', 'ClientKPIItemController@destroy');

});

$router->group(['middleware' => 'client_auth'], function() use($router) {
    $router->get('/client-details', 'ClientPortalController@profile');
    $router->get('/client-score', 'ClientPortalController@scoreDetails');
    $router->get('/client-kpi-score/{slug}', 'ClientPortalController@kpiScoreDetails');
});
