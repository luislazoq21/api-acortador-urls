<?php

namespace Core;

class Router
{
    private array $routes = [];
    private Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function addRoute(string $method, string $uri, array $handler, array $middleware = [])
    {
        $method = strtoupper($method);
        $uri = rtrim($uri, '/') ?: '/';
        $this->routes[$method][$uri] = [
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function get($uri, $handler)
    {
        $this->addRoute('GET', $uri, $handler);
    }

    public function dispatch(Request $request)
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        if (!isset($this->routes[$method][$uri])) {
            return $this->notFound();
        }

        $route = $this->routes[$method][$uri];
        $handler = $route['handler'];

        if (!is_array($handler) || count($handler) !== 2) {
            return $this->response->json(['error' => 'Invalid route handler'], 500);
        }

        [$controller, $methodName] = $handler;

        if (!class_exists($controller)) {
            return $this->response->json(['error' => "Controller '$controller' not found"], 500);
        }

        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $methodName)) {
            return $this->response->json(['error' => "Method '$methodName' not found in '$controller'"], 500);
        }

        $data = $controllerInstance->$methodName();
        return $this->response->json(['data' => $data]);
    }

    public function getRoutes()
    {
        return $this->response->json(['routes' => $this->routes]);
    }

    public function notFound()
    {
        return $this->response->json(['error' => 'Route not found'], 404);
    }
}
