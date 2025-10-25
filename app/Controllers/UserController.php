<?php

namespace App\Controllers;

class UserController
{
    public function index()
    {
        return [
            [
                'id' => 1,
                'name' => 'Luis',
            ],
            [
                'id' => 2,
                'name' => 'Karina',
            ],
        ];
    }
}