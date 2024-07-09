<?php

declare(strict_types=1);

namespace App\ApiInput\User;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserInputDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank]
    public ?string $firstName = null;

    #[Assert\NotBlank]
    public ?string $lastName = null;

    #[Assert\NotBlank]
    public ?string $password = null;
}
