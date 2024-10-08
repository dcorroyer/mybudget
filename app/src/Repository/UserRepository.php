<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use My\RestBundle\Repository\Common\AbstractEntityRepository;
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

    // TODO: to test during improvement user creation task
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
