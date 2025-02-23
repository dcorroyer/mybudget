<?php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\Common\AbstractEntityRepository;
use App\Entity\Account;

/**
 * @extends AbstractEntityRepository<Account>
 */
class AccountRepository extends AbstractEntityRepository
{
    #[\Override]
    public function getEntityClass(): string
    {
        return Account::class;
    }
}
