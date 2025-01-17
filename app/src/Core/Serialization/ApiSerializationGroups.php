<?php

declare(strict_types=1);

namespace App\Core\Serialization;

final class ApiSerializationGroups
{
    final public const API_SUCCESS = '__api_success__';
    final public const API_ERROR = '__api_error__';
    final public const API_ERROR_CODE = '__api_error_code__';
    final public const PAGINATED_LIST = 'PAGINATED_LIST';
}
