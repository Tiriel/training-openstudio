<?php

namespace App\Dto;

interface EntityCastableInterface
{
    public function toEntity(): object;
}
