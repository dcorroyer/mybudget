<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\ApiInput\User\CreateUserInputDto;
use App\ApiInput\User\UpdateUserInputDto;
use App\State\User\CreateUserProcessor;
use App\State\User\DeleteUserProcessor;
use App\State\User\UpdateUserProcessor;
use App\State\User\UserCollectionStateProvider;
use App\State\User\UserStateProvider;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    uriTemplate: '/users',
    shortName: 'User',
    operations: [
        new GetCollection(uriTemplate: '/users', provider: UserCollectionStateProvider::class),
        new Get(uriTemplate: '/users/{id}', provider: UserStateProvider::class),
        new Post(uriTemplate: '/register', input: CreateUserInputDto::class, processor: CreateUserProcessor::class),
        new Delete(uriTemplate: '/users/{id}', input: null, read: false, processor: DeleteUserProcessor::class),
        new Patch(uriTemplate: '/users/{id}', input: UpdateUserInputDto::class, read: false, processor: UpdateUserProcessor::class),
    ],
)]
class UserResource
{
    public Uuid $id;

    public ?string $email = null;

    public ?string $firstName = null;

    public ?string $lastName = null;

    /**
     * @var list<string>|null The user roles
     */
    public ?array $roles = [];

    public ?string $password = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return list<string>|null
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @param list<string>|null $roles
     */
    public function setRoles(?array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }
}
