<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\Tests\Common\Factory\UserFactory;
use App\Tests\Functional\TestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
#[Group('integration')]
#[Group('controller')]
#[Group('user')]
#[Group('user-controller')]
class GetUserControllerTest extends TestBase
{
    private const API_ENDPOINT = '/api/users';

    #[TestDox('When you call GET /api/users/me, it should return the user informations')]
    #[Test]
    public function getUserController_WhenDataOk_ReturnsUser(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        // ACT
        $response = $this->clientRequest(Request::METHOD_GET, self::API_ENDPOINT . '/me');
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');
        $this->assertSame($user->getId(), $responseData['id']);
        $this->assertSame($user->getEmail(), $responseData['email']);
    }

    #[TestDox('When you call GET /api/users/me, it should return unauthorized')]
    #[Test]
    public function getUserController_WhenNotLogged_ReturnsUnauthorized(): void
    {
        // ACT
        $this->clientRequest(Request::METHOD_GET, self::API_ENDPOINT . '/me');

        // ASSERT
        $this->assertSame(
            Response::HTTP_UNAUTHORIZED,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        self::assertResponseFormatSame('json');
    }
}
