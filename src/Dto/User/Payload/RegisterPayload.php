<?php

declare(strict_types=1);

namespace App\Dto\User\Payload;

use My\RestBundle\Contract\PayloadInterface;

class RegisterPayload implements PayloadInterface
{
    private string $email;

    private string $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
