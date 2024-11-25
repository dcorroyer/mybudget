<?php

declare(strict_types=1);

namespace App\Dto\Account\Payload;

use Doctrine\DBAL\Types\Types;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AccountPayload implements PayloadInterface
{
    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    public string $name;
}
