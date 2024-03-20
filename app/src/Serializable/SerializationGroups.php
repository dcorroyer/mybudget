<?php

declare(strict_types=1);

namespace App\Serializable;

final class SerializationGroups
{
    public const EXPENSE_CREATE = 'EXPENSE_CREATE';

    public const EXPENSE_UPDATE = 'EXPENSE_UPDATE';

    public const EXPENSE_DELETE = 'EXPENSE_DELETE';

    public const EXPENSE_GET = 'EXPENSE_GET';

    public const EXPENSE_LIST = 'EXPENSE_LIST';

    public const EXPENSE_CATEGORY_LIST = 'EXPENSE_CATEGORY_LIST';

    public const EXPENSE_CATEGORY_UPDATE = 'EXPENSE_CATEGORY_UPDATE';

    public const EXPENSE_CATEGORY_GET = 'EXPENSE_CATEGORY_GET';

    public const INCOME_CREATE = 'INCOME_CREATE';

    public const INCOME_UPDATE = 'INCOME_UPDATE';

    public const INCOME_DELETE = 'INCOME_DELETE';

    public const INCOME_GET = 'INCOME_GET';

    public const INCOME_LIST = 'INCOME_LIST';

    public const BUDGET_CREATE = 'BUDGET_CREATE';

    public const BUDGET_DELETE = 'BUDGET_DELETE';

    public const BUDGET_UPDATE = 'BUDGET_UPDATE';

    public const BUDGET_GET = 'BUDGET_GET';

    public const BUDGET_LIST = 'BUDGET_LIST';

    public const USER_CREATE = 'USER_CREATE';

    public const USER_GET = 'USER_GET';
}
