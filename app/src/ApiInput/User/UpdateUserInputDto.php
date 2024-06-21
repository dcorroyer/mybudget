<?php

declare(strict_types=1);

namespace App\ApiInput\User;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserInputDto
{
    #[Assert\When('this.email ?? "" != ""', constraints: [new Assert\Email()])]
    public readonly string $email;

    public readonly string $password;

    public readonly string $firstName;

    public readonly string $lastName;
}
