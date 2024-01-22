<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Authentication;

use App\Dto\User\Payload\RegisterPayload;
use App\Dto\User\Response\RegisterResponse;
use App\Service\UserService;
use App\Tests\Common\Factory\UserFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

#[Group('integration')]
#[Group('controller')]
#[Group('user')]
#[Group('user-controller')]
class RegisterControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/register';

    private KernelBrowser $client;

    private UserService $userService;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->userService = $this->createMock(UserService::class);

        $container = self::getContainer();
        $container->set(UserService::class, $this->userService);
    }

    #[TestDox('When you call POST /api/register, it should create and return the user')]
    #[Test]
    public function createRegisterController_WhenDataOk_ReturnsUser(): void
    {
        // ARRANGE
        $user = UserFactory::new()->withoutPersisting()->create()->object();

        $payload = [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => 'password',
        ];

        $userResponse = (new RegisterResponse())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setId($user->getId())
            ->setEmail($user->getEmail());

        $this->userService
            ->expects($this->once())
            ->method('create')
            ->willReturn($userResponse);

        // ACT
        $endpoint = self::API_ENDPOINT;
        $this->client->request(
            method: 'POST',
            uri: $endpoint,
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode($payload)
        );
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertEquals($userResponse->getId(), $data['id']);
    }
}
