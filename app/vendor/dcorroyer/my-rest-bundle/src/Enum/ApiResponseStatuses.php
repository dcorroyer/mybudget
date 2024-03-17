<?php

declare(strict_types=1);

namespace My\RestBundle\Enum;

enum ApiResponseStatuses: string
{
    case STATUS_SUCCESS = 'SUCCESS';
    case STATUS_ERROR = 'ERROR';
}
