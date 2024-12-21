<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\Shared\Doctrine\Repository\AbstractEntityRepository;
use App\User\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends AbstractEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 */
class UserRepository extends AbstractEntityRepository implements PasswordUpgraderInterface
{
    #[\Override]
    public function getEntityClass(): string
    {
        return User::class;
    }

    #[\Override]
    public function upgradePassword(
        PasswordAuthenticatedUserInterface $passwordAuthenticatedUser,
        string $newHashedPassword
    ): void {
        if (! $passwordAuthenticatedUser instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of "%s" are not supported.', User::class));
        }

        $passwordAuthenticatedUser->setPassword($newHashedPassword);
        $this->getEntityManager()
            ->persist($passwordAuthenticatedUser)
        ;
        $this->getEntityManager()
            ->flush()
        ;
    }
}