<?php

declare(strict_types=1);

namespace App\Tests\Functional\Authentication;

use App\Tests\Functional\TestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
#[Group('functional')]
#[Group('controller')]
#[Group('register')]
#[Group('register-controller')]
class RegisterControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/register';

    #[TestDox('When you call POST /api/register, it should create and return the user')]
    #[Test]
    public function createRegisterController_WhenDataOk_ReturnsUser(): void
    {
        // ARRANGE
        $payload = [
            'firstName' => 'john',
            'lastName' => 'doe',
            'email' => 'john.doe@admin.local',
            'password' => 'password',
        ];

        // ACT
        $response = $this->clientRequest(Request::METHOD_POST, self::API_ENDPOINT, $payload);
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');
        self::assertSame($payload['email'], $responseData['email']);
    }
}
