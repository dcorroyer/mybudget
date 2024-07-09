<?php

declare(strict_types=1);

namespace App\ApiInput\User;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserInputDto
{
    #[Assert\When('this.email ?? "" != ""', constraints: [new Assert\Email()])]
    #[Assert\Email]
    public ?string $email;

    public ?string $firstName;

    public ?string $lastName;

    public ?string $password;
}
