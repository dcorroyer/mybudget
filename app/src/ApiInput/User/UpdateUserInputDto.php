<?php

declare(strict_types=1);

namespace App\ApiInput\User;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserInputDto
{
    #[Assert\When('this.email ?? "" != ""', constraints: [new Assert\Email()])]
    #[Assert\Email]
    protected ?string $email;

    protected ?string $firstName;

    protected ?string $lastName;

    protected ?string $password;
}
