<?php

namespace Core;

class Response
{
    public function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($data);
        exit;
    }

    public function setStatus(int $code): void
    {
        http_response_code($code);
    }

    public function setCors(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    public function redirect(string $url, int $statusCode = 302): void
    {
        http_response_code($statusCode);
        header("Location: $url");
        exit;
    }
}