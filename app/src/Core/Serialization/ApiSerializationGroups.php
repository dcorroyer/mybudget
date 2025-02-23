<?php

declare(strict_types=1);

namespace App\Core\Serialization;

final class ApiSerializationGroups
{
    final public const string API_SUCCESS = '__api_success__';
    final public const string API_ERROR = '__api_error__';
    final public const string API_ERROR_CODE = '__api_error_code__';
    final public const string PAGINATED_LIST = 'PAGINATED_LIST';
}
