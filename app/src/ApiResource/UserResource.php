<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\ApiInput\User\CreateUserInputDto;
use App\ApiInput\User\UpdateUserInputDto;
use App\State\User\DeleteUserProcessor;
use App\State\User\GetMeStateProvider;
use App\State\User\RegisterUserProcessor;
use App\State\User\UpdateUserProcessor;
use Rekalogika\Mapper\CollectionInterface;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    uriTemplate: '/users',
    shortName: 'User',
    operations: [
        new Get(uriTemplate: '/me', provider: GetMeStateProvider::class),
        new Post(uriTemplate: '/register', input: CreateUserInputDto::class, processor: RegisterUserProcessor::class),
        new Delete(uriTemplate: '/me', input: null, read: false, processor: DeleteUserProcessor::class),
        new Patch(uriTemplate: '/me', input: UpdateUserInputDto::class, read: false, processor: UpdateUserProcessor::class),
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
     * @var CollectionInterface<int, BudgetResource>|null
     */
    public ?CollectionInterface $budgets = null;
}
