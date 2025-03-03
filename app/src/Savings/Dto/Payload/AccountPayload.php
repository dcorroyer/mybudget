<?php

declare(strict_types=1);

namespace App\Savings\Dto\Payload;

use Doctrine\DBAL\Types\Types;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(schema: 'AccountPayload', description: 'Data for creating or updating an account', required: ['name'])]
class AccountPayload
{
    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    #[OA\Property(description: 'Account name', example: 'Current Account', type: 'string')]
    public string $name;
}
