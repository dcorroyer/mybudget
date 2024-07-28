<?php

declare(strict_types=1);

namespace App\Dto\User\Payload;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterPayload
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

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }
}
