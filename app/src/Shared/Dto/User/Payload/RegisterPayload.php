<?php

declare(strict_types=1);

namespace App\Shared\Dto\User\Payload;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterPayload
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    public string $firstName;

    #[Assert\NotBlank]
    public string $lastName;

    #[Assert\NotBlank]
    public string $password;
}
