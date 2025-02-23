<?php

declare(strict_types=1);

namespace App\Dto\Savings\Payload;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class AccountPayload
{
    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    public string $name;
}
