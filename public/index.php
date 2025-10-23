<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Core\Database;
use Core\Request;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$request = new Request();
var_dump($request->getUri());