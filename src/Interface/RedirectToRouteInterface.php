<?php

namespace App\Interface;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface RedirectToRouteInterface
{
    public function __invoke(string $route, array $parameters = [], int $status = 302): RedirectResponse;
}
