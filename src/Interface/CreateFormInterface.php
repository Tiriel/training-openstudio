<?php

namespace App\Interface;

use Symfony\Component\Form\FormInterface;

interface CreateFormInterface
{
    public function __invoke(string $type, mixed $data = null, array $options = []): FormInterface;
}
