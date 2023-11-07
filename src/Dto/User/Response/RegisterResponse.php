<?php

declare(strict_types=1);

namespace App\Dto\User\Response;

use App\Serializable\SerializationGroups;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class RegisterResponse implements PayloadInterface
{
    #[Serializer\Groups([SerializationGroups::USER_CREATE])]
    private int $id;

    #[Serializer\Groups([SerializationGroups::USER_CREATE])]
    private string $email;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
