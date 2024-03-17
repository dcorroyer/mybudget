<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Authentication;

use App\Dto\User\Response\UserResponse;
use App\Service\UserService;
use App\Tests\Common\Factory\UserFactory;
use App\Tests\Common\Helper\MockHelperTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
class RegisterControllerTest extends WebTestCase
{
    use MockHelperTrait;

    private const URI = '/api/register';

    private const METHOD = 'POST';

    private UserService $userService;

    private KernelBrowser $client;

    protected function setup(): void
    {
        $this->client = $this->createClient();

        $this->userService = $this->createMockAndSetToContainer(UserService::class);
    }

    #[Test]
    #[TestDox('When call /api/register  without Email, it should return error')]
    public function RegisterControllerTestWithoutEmail(): void
    {
        // ARRANGE
        $payload = [
            'firstName' => 'Cordia Hirthe V',
            'lastName' => 'Rebecca Marks',
            'password' => 'Isidro Kutch I',
        ];

        // ACT
        $this->client->request(method: self::METHOD, uri: self::URI, server: [
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($payload));

        // ASSERT
        $this->assertResponseStatusCodeSame(422);
    }

    #[Test]
    #[TestDox('When call /api/register  without FirstName, it should return error')]
    public function RegisterControllerTestWithoutFirstName(): void
    {
        // ARRANGE
        $payload = [
            'email' => 'Miss Laurianne Hermann',
            'lastName' => 'Evan Fadel',
            'password' => 'Prof. Shyann Pagac',
        ];

        // ACT
        $this->client->request(method: self::METHOD, uri: self::URI, server: [
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($payload));

        // ASSERT
        $this->assertResponseStatusCodeSame(422);
    }

    #[Test]
    #[TestDox('When call /api/register  without LastName, it should return error')]
    public function RegisterControllerTestWithoutLastName(): void
    {
        // ARRANGE
        $payload = [
            'email' => 'Emmitt Roob',
            'firstName' => 'Miss Marquise Dickinson II',
            'password' => 'Arianna Muller',
        ];

        // ACT
        $this->client->request(method: self::METHOD, uri: self::URI, server: [
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($payload));

        // ASSERT
        $this->assertResponseStatusCodeSame(422);
    }

    #[Test]
    #[TestDox('When call /api/register  without Password, it should return error')]
    public function RegisterControllerTestWithoutPassword(): void
    {
        // ARRANGE
        $payload = [
            'email' => 'Josianne Brekke',
            'firstName' => 'Queen Spencer',
            'lastName' => 'Nicola Sporer',
        ];

        // ACT
        $this->client->request(method: self::METHOD, uri: self::URI, server: [
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($payload));

        // ASSERT
        $this->assertResponseStatusCodeSame(422);
    }

    #[Test]
    #[TestDox('When call /api/register  without Parameters, it should return error')]
    public function RegisterControllerTestWithoutParameters(): void
    {
        // ACT
        $this->client->request(method: self::METHOD, uri: self::URI, server: [
            'CONTENT_TYPE' => 'application/json',
        ],);

        // ASSERT
        $this->assertResponseStatusCodeSame(422);
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

        $userResponse = (new UserResponse())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setId($user->getId())
            ->setEmail($user->getEmail())
        ;

        $this->userService
            ->expects($this->once())
            ->method('create')
            ->willReturn($userResponse)
        ;

        // ACT
        $endpoint = self::URI;
        $this->client->request(method: 'POST', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($payload));
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertSame($userResponse->getId(), $data['id']);
    }
}
