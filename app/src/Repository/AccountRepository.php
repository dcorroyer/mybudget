<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use App\Shared\Doctrine\Repository\AbstractEntityRepository;

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
