<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\User\Payload\RegisterPayload;
use App\Dto\User\Response\UserResponse;
use App\Entity\User;
use App\Repository\UserRepository;
use My\RestBundle\Helper\DtoToEntityHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly DtoToEntityHelper $dtoToEntityHelper,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function create(RegisterPayload $registerPayload): UserResponse
    {
        $user = new User();

        /** @var User $user */
        $user = $this->dtoToEntityHelper->create($registerPayload, $user);

        $password = $this->passwordHasher->hashPassword($user, $registerPayload->getPassword());

        $user->setPassword($password);

        $this->userRepository->save($user, true);

        return (new UserResponse())
            ->setId($user->getId())
            ->setEmail($user->getEmail())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
        ;
    }

    public function get(string $userIdentifier): UserResponse
    {
        $user = $this->userRepository->findOneBy([
            'email' => $userIdentifier,
        ]);

        if ($user === null) {
            throw new NotFoundHttpException("User {$userIdentifier} not found");
        }

        return (new UserResponse())
            ->setId($user->getId())
            ->setEmail($user->getEmail())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
        ;
    }
}
