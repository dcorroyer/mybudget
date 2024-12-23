<?php

declare(strict_types=1);

namespace App\User\Dto\Payload;

use App\Shared\Api\Dto\Contract\PayloadInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterPayload implements PayloadInterface
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
