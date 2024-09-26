<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Autoload classes using Composer PSR-4
require_once __DIR__ . '/../vendor/autoload.php';

// Use the Router and Request classes
use App\Core\Router;
use App\Core\Request;

// Initialize Router
$router = new Router(new Request());

// Define routes (relative to the /pdo-mysql/public/ directory)
$router->get('/users', 'App\Controllers\UserController@index');
$router->post('/users', 'App\Controllers\UserController@store');

// Dispatch the request
$router->resolve();
