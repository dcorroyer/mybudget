<?php

declare(strict_types=1);

namespace My\RestBundle\Adapter;

interface ErrorCodesInterface
{
    public function getErrorCode(): string;
}
