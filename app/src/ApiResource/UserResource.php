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
use App\Entity\Budget;
use App\State\User\CreateUserProcessor;
use App\State\User\DeleteUserProcessor;
use App\State\User\UpdateUserProcessor;
use App\State\User\UserCollectionStateProvider;
use App\State\User\UserStateProvider;
use Doctrine\Common\Collections\Collection;
use Rekalogika\Mapper\CollectionInterface;
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
    public ?Uuid $id = null;

    public ?string $email = null;

    public ?string $firstName = null;

    public ?string $lastName = null;

    /**
     * @var list<string>|null The user roles
     */
    public ?array $roles = [];

    public ?string $password = null;

    /**
     * @var ?CollectionInterface<int, Budget>
     */
    public ?CollectionInterface $budgets = null;
}
