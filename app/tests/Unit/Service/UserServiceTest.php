<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Shared\Dto\Payload\RegisterPayload;
use App\Shared\Entity\User;
use App\Shared\Exception\UserNotFoundException;
use App\Shared\Repository\UserRepository;
use App\Shared\Service\UserService;
use App\Tests\Common\Factory\UserFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('service')]
#[Group('user')]
#[Group('user-service')]
final class UserServiceTest extends TestCase
{
    use Factories;
    private UserService $userService;

    private UserRepository $userRepository;

    private UserPasswordHasherInterface $passwordHasher;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $this->userService = new UserService($this->userRepository, $this->passwordHasher);
    }

    #[TestDox('When calling create user, it should create and return a new user')]
    #[Test]
    public function createUserService_WhenDataOk_ReturnsUser(): void
    {
        // ARRANGE
        $user = UserFactory::createOne([
            'id' => 1,
        ]);

        $registerPayload = (new RegisterPayload());
        $registerPayload->email = $user->getEmail();
        $registerPayload->firstName = $user->getFirstName();
        $registerPayload->lastName = $user->getLastName();
        $registerPayload->password = $user->getPassword();

        $this->passwordHasher
            ->expects($this->once())
            ->method('hashPassword')
            ->willReturn($user->getPassword())
        ;

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (User $user): void {
                $user->setId(1);
            })
        ;

        // ACT
        $userResponse = $this->userService->create($registerPayload);

        // ASSERT
        self::assertInstanceOf(User::class, $user);
        self::assertSame($user->getId(), $userResponse->id);
        self::assertSame($user->getEmail(), $userResponse->email);
    }

    #[TestDox('When calling get user, it should returns the connected user')]
    #[Test]
    public function getUserService_WhenDataOk_ReturnsUser(): void
    {
        // ARRANGE
        $user = UserFactory::createOne([
            'id' => 1,
        ]);

        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn($user)
        ;

        // ACT
        $userResponse = $this->userService->get($user->getEmail());

        // ASSERT
        self::assertInstanceOf(User::class, $user);
        self::assertSame($user->getId(), $userResponse->id);
        self::assertSame($user->getEmail(), $userResponse->email);
    }

    #[TestDox('When calling get user with a bad email, it should returns NotFoundHttpException')]
    #[Test]
    public function getUserService_WhenDataKO_ReturnsNotFoundHttpException(): void
    {
        // ASSERT
        $this->expectException(UserNotFoundException::class);

        // ARRANGE
        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null)
        ;

        // ACT
        $this->userService->get('bad-email');
    }
}
