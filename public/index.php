<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$request = new Request();
$router = new Router();
$response = new Response();


$router->addRoute('GET', '/users/', [UserController::class, 'index']);

$router->dispatch($request);