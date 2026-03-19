<?php

namespace App\Interface;

use Symfony\Component\HttpFoundation\Response;

interface RenderInterface
{
    public function __invoke(string $view, array $parameters = [], ?Response $response = null): Response;
}
