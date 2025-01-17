<?php

declare(strict_types=1);

namespace App\Shared\Service;

use App\Shared\Dto\User\Payload\RegisterPayload;
use App\Shared\Dto\User\Response\UserResponse;
use App\Shared\Entity\User;
use App\Shared\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function create(RegisterPayload $registerPayload): UserResponse
    {
        $user = new User();

        $user->setEmail($registerPayload->email)
            ->setFirstName($registerPayload->firstName)
            ->setLastName($registerPayload->lastName)
        ;

        $password = $this->passwordHasher->hashPassword($user, $registerPayload->password);

        $user->setPassword($password);

        $this->userRepository->save($user, true);

        return new UserResponse(
            id: $user->getId(),
            email: $user->getEmail(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
        );
    }

    public function get(string $userIdentifier): UserResponse
    {
        $user = $this->userRepository->findOneBy([
            'email' => $userIdentifier,
        ]);

        if ($user === null) {
            throw new NotFoundHttpException("User {$userIdentifier} not found");
        }

        return new UserResponse(
            id: $user->getId(),
            email: $user->getEmail(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
        );
    }
}
