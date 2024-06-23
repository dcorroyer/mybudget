<?php

declare(strict_types=1);

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\UserResource;
use App\Repository\UserRepository;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProvider;

/**
 * @extends AbstractProvider<UserResource>
 */
class UserStateProvider extends AbstractProvider
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        $user = $this->userRepository->find($uriVariables['id']) ?? throw new NotFoundException('User not found');

        return $this->map($user, UserResource::class);
    }
}
