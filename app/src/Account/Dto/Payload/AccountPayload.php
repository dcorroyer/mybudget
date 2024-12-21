<?php

declare(strict_types=1);

namespace App\Account\Dto\Payload;

use App\Shared\Api\Dto\Contract\PayloadInterface;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class AccountPayload implements PayloadInterface
{
    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    public string $name;
}
