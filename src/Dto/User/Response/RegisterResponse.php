<?php

declare(strict_types=1);

namespace App\Dto\User\Response;

use App\Serializable\SerializationGroups;
use App\Trait\Response\IdResponseTrait;
use My\RestBundle\Contract\ResponseInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class RegisterResponse implements ResponseInterface
{
    use IdResponseTrait;

    #[Serializer\Groups([SerializationGroups::USER_CREATE])]
    private string $email;

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
