<?php

declare(strict_types=1);

namespace App\Serializable;

final class SerializationGroups
{
    public const INCOME_CREATE = 'INCOME_CREATE';

    public const INCOME_UPDATE = 'INCOME_UPDATE';

    public const INCOME_DELETE = 'INCOME_DELETE';

    public const INCOME_GET = 'INCOME_GET';

    public const INCOME_LIST = 'INCOME_LIST';

    public const USER_CREATE = 'USER_CREATE';

    public const EXPENSE_CREATE = 'EXPENSE_CREATE';

    public const EXPENSE_UPDATE = 'EXPENSE_UPDATE';

    public const EXPENSE_DELETE = 'EXPENSE_DELETE';

    public const EXPENSE_GET = 'EXPENSE_GET';

    public const EXPENSE_LIST = 'EXPENSE_LIST';

    public const EXPENSE_CATEGORY_LIST = 'EXPENSE_CATEGORY_LIST';

    public const EXPENSE_CATEGORY_UPDATE = 'EXPENSE_CATEGORY_UPDATE';

    public const EXPENSE_CATEGORY_GET = 'EXPENSE_CATEGORY_GET';

    public const TRACKING_CREATE = 'TRACKING_CREATE';

    public const TRACKING_DELETE = 'TRACKING_DELETE';

    public const TRACKING_UPDATE = 'TRACKING_UPDATE';

    public const TRACKING_GET = 'TRACKING_GET';

    public const TRACKING_LIST = 'TRACKING_LIST';
}
