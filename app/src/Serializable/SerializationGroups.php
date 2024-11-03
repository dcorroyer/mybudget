<?php

declare(strict_types=1);

namespace App\Serializable;

final class SerializationGroups
{
    public const string BUDGET_CREATE = 'BUDGET_CREATE';

    public const string BUDGET_UPDATE = 'BUDGET_UPDATE';

    public const string BUDGET_GET = 'BUDGET_GET';

    public const string BUDGET_LIST = 'BUDGET_LIST';

    public const string ACCOUNT_CREATE = 'ACCOUNT_CREATE';

    public const string ACCOUNT_UPDATE = 'ACCOUNT_UPDATE';

    public const string ACCOUNT_GET = 'ACCOUNT_GET';

    public const string ACCOUNT_LIST = 'ACCOUNT_LIST';

    public const string TRANSACTION_CREATE = 'TRANSACTION_CREATE';

    public const string TRANSACTION_UPDATE = 'TRANSACTION_UPDATE';

    public const string TRANSACTION_GET = 'TRANSACTION_GET';

    public const string TRANSACTION_LIST = 'TRANSACTION_LIST';

    public const string USER_CREATE = 'USER_CREATE';

    public const string USER_GET = 'USER_GET';

    public const string BALANCE_HISTORY_GET = 'BALANCE_HISTORY_GET';
}
