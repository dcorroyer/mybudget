<?php

declare(strict_types=1);

namespace App\ApiInput\Budget\Dependencies;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class ExpenseCategoryInputDto
{
    #[Assert\Type(Types::INTEGER)]
    public ?int $id = null;

    #[Assert\Type(Types::STRING)]
    public string $name;
}
