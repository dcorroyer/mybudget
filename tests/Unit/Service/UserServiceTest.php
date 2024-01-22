<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\User\Payload\RegisterPayload;
use App\Dto\User\Response\RegisterResponse;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Tests\Common\Factory\UserFactory;
use My\RestBundle\Helper\DtoToEntityHelper;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Test\Factories;

#[Group('unit')]
#[Group('service')]
#[Group('user')]
#[Group('user-service')]
class UserServiceTest extends TestCase
{
    use SerializerTrait;
    use Factories;

    private UserService $userService;

    private UserRepository $userRepository;

    private DtoToEntityHelper $dtoToEntityHelper;

    private UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->dtoToEntityHelper = $this->createMock(DtoToEntityHelper::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $this->userService = new UserService($this->userRepository, $this->dtoToEntityHelper, $this->passwordHasher);
    }

    #[TestDox('When calling create expense category, it should create and return a new expense category')]
    #[Test]
    public function createUserService_WhenDataOk_ReturnsUser()
    {
        // ARRANGE
        $user = UserFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object();

        $userPayload = (new RegisterPayload())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setEmail($user->getEmail())
            ->setPassword('password');

        $this->dtoToEntityHelper
            ->expects($this->once())
            ->method('create')
            ->willReturn($user);

        $this->passwordHasher
            ->expects($this->once())
            ->method('hashPassword')
            ->willReturn($user->getPassword());

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (User $user) {
                $user->setId(1);
            });

        // ACT
        $userResponse = $this->userService->create($userPayload);

        // ASSERT
        $this->assertInstanceOf(RegisterResponse::class, $userResponse);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->getId(), $userResponse->getId());
        $this->assertEquals($user->getEmail(), $userResponse->getEmail());
    }
}
