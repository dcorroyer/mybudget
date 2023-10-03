<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security;

use App\Factory\UserFactory;
use App\Service\Security\SecurityService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Test\Factories;

class SecurityServiceTest extends TestCase
{
    use Factories;

    public function testRegisterOKReturnsUser(): void
    {
        // ARRANGE
        $form = $this->createMock(FormInterface::class);
        $userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);

        $securityService = new SecurityService($userPasswordHasher, $em);
        $user = UserFactory::new()->create()->object();

        $form->expects($this->once())
            ->method('get')
            ->with('password')
            ->willReturnSelf();

        $form->expects($this->once())
            ->method('getData')
            ->willReturn('password123');

        $userPasswordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($this->identicalTo($user), 'password123')
            ->willReturn('hashedPassword123');

        $em->expects($this->once())
            ->method('persist')
            ->with($this->identicalTo($user));

        $em->expects($this->once())
            ->method('flush');

        // ACT
        $userResult = $securityService->register($user, $form);

        // ASSERT
        $this->assertSame($user, $userResult);
    }
}
