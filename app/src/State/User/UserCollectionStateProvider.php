<?php

declare(strict_types=1);

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\UserResource;
use App\Repository\UserRepository;
use Rekalogika\ApiLite\State\AbstractProvider;

/**
 * @extends AbstractProvider<UserResource>
 */
class UserCollectionStateProvider extends AbstractProvider
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->mapCollection(collection: $this->userRepository, target: UserResource::class, operation: $operation, context: $context);
    }
}