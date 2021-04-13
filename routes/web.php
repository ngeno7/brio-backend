<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\GlobalKPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

$router->get('/', function (Request $request) use ($router) {
    return [$router->app->version(), $request->bearerToken()];
});

$router->post('/client-login', 'UserController@logInClient');
$router->post('/user-login', 'UserController@logInUser');

$router->post('/get-verification-code', 'ClientController@resetPasswordInit');
$router->post('/reset-password', 'ClientController@resetPassword');

$router->get('/test-mail', function() {
    $data = ['email' => 'Ngeno', 'password' => '123456'];
    Mail::send('mail', $data, function($message) {
        $message->to('hillaryng14@gmail.com')->subject('Brio Account');
    });
});

$router->group(['middleware' => 'auth'], function() use($router) {
    $router->get('user', function() {
        return Auth::user();
    });
    $router->get('users', 'UserController@index');
    $router->post('users', 'UserController@create');
    $router->post('users/{id}', 'UserController@update');
    $router->delete('users/{id}', 'UserController@destroy');
    $router->get('users/clients/{id}', 'UserController@clients');

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
    $router->put('/clients/display-texts/{slug}', 'ClientController@updateDisplayTexts');
    $router->delete('/clients/{slug}', 'ClientController@destroy');

    $router->get('/global-client-kpis/{clientSlug}', 'GlobalClientKPIController@index');
    $router->get('/global-client-kpis/{clientSlug}/{kpiSlug}', 'GlobalClientKPIController@kpiItems');
    $router->post('/global-client-kpis/{clientSlug}', 'GlobalClientKPIController@storeScore');
    $router->delete('/global-client-kpis/{clientSlug}/{kpiSlug}', 'GlobalClientKPIController@destroy');
    $router->put('/global-client-kpis/display-texts/{clientSlug}', 'GlobalClientKPIController@updateDisplayText');

    $router->get('/client-kpis-all/{client}', 'ClientKPIController@index');
    $router->get('/client-kpis/{client}/{kpi}', 'ClientKPIController@clientKPI');
    $router->post('/client-kpis/{clientSlug}/{kpiSlug}', 'ClientKPIController@store');
    $router->get('/client-kpis-score/{client}', 'ClientKPIController@score');

    $router->post('/client-kpi-item/{kpiSlug}/{clientSlug}', 'ClientKPIItemController@store');
    $router->delete('/client-kpi-item/{id}', 'ClientKPIItemController@destroy');

});

$router->group(['middleware' => 'client_auth'], function() use($router) {
    $router->post('/client-rating/{slug}', 'ClientController@updateRating');
    $router->get('/client-details', 'ClientPortalController@profile');
    $router->get('/client-score', 'ClientPortalController@scoreDetails');
    $router->get('/client-kpi-score/{slug}', 'ClientPortalController@kpiScoreDetails');
});
