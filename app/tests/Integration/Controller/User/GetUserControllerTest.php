<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\User;

use App\Entity\User;
use App\Service\UserService;
use App\Tests\Common\Factory\UserFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('integration')]
#[Group('controller')]
#[Group('user')]
#[Group('user-controller')]
class GetUserControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/users';

    private KernelBrowser $client;

    private UserService $userService;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $container = self::getContainer();

        $this->userService = $this->createMock(UserService::class);

        $container->set(UserService::class, $this->userService);
    }

    #[TestDox('When you call GET /api/users/me, it should return the user informations')]
    #[Test]
    public function getUserController_WhenDataOk_ReturnsUser(): void
    {
        // ARRANGE
        $user = UserFactory::new()->withoutPersisting()->create()->object();

        $userResponse = (new User())
            ->setEmail($user->getEmail())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setId($user->getId())
        ;

        $this->userService
            ->expects($this->once())
            ->method('get')
            ->willReturn($userResponse)
        ;

        $this->client->loginUser($user);

        $endpoint = self::API_ENDPOINT . '/me';

        // ACT
        $this->client->request(method: 'GET', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertSame($user->getId(), $data['id']);
        $this->assertSame($user->getEmail(), $data['email']);
    }
}
