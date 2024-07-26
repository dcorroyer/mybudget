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
class GetMeStateProvider extends AbstractProvider
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        $user = $this->userRepository->find($this->getUser()) ?? throw new NotFoundException('User not found');

        return $this->map($user, UserResource::class);
    }
}
