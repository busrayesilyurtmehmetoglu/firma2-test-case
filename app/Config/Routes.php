<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('auth/register', 'AuthController::register');
$routes->post('auth/login', 'AuthController::login');

$routes->group('api', ['filter' => 'jwt'], function($routes) {
    $routes->post('external/rest', 'ExternalApiController::rest');
    // İleride SOAP ve diğer endpointler de buraya eklenebilir
});
