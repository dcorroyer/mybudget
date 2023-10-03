<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function register(User $user, FormInterface $form): User
    {
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $form->get('password')->getData()));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
