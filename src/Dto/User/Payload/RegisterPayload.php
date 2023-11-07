<?php

declare(strict_types=1);

namespace App\Dto\User\Payload;

use App\Serializable\SerializationGroups;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class RegisterPayload implements PayloadInterface
{
    #[Serializer\Groups([SerializationGroups::USER_CREATE])]
    private string $email;

    #[Serializer\Groups([SerializationGroups::USER_CREATE])]
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
