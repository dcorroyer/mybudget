<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\User\Payload\RegisterPayload;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function create(RegisterPayload $registerPayload): User
    {
        $user = new User();

        $user->setEmail($registerPayload->email)
            ->setFirstName($registerPayload->firstName)
            ->setLastName($registerPayload->lastName)
        ;

        $password = $this->passwordHasher->hashPassword($user, $registerPayload->password);

        $user->setPassword($password);

        $this->userRepository->save($user, true);

        return $user;
    }

    public function get(string $userIdentifier): User
    {
        $user = $this->userRepository->findOneBy([
            'email' => $userIdentifier,
        ]);

        if ($user === null) {
            throw new NotFoundHttpException("User {$userIdentifier} not found");
        }

        return $user;
    }
}
