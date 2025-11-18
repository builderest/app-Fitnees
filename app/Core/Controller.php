<?php
namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        echo \view($view, $data);
    }
}
