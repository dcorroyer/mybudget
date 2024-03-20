<?php

declare(strict_types=1);

namespace App\Dto\User\Response;

use App\Serializable\SerializationGroups;
use App\Trait\Response\IdResponseTrait;
use My\RestBundle\Contract\ResponseInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class UserResponse implements ResponseInterface
{
    use IdResponseTrait;

    #[Serializer\Groups([SerializationGroups::USER_CREATE, SerializationGroups::USER_GET])]
    private string $email;

    #[Serializer\Groups([SerializationGroups::USER_CREATE, SerializationGroups::USER_GET])]
    private string $firstName;

    #[Serializer\Groups([SerializationGroups::USER_CREATE, SerializationGroups::USER_GET])]
    private string $lastName;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }
}
