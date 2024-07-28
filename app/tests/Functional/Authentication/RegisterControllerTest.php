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
#[Group('integration')]
#[Group('controller')]
#[Group('register')]
#[Group('register-controller')]
class RegisterControllerTest extends TestBase
{
    private const API_ENDPOINT = '/api/register';

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
        $this->clientRequest(Request::METHOD_POST, self::API_ENDPOINT, $payload);

        // ASSERT
        self::assertResponseStatusCodeSame(422);
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
        $this->clientRequest(Request::METHOD_POST, self::API_ENDPOINT, $payload);

        // ASSERT
        self::assertResponseStatusCodeSame(422);
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
        $this->clientRequest(Request::METHOD_POST, self::API_ENDPOINT, $payload);

        // ASSERT
        self::assertResponseStatusCodeSame(422);
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
        $this->clientRequest(Request::METHOD_POST, self::API_ENDPOINT, $payload);

        // ASSERT
        self::assertResponseStatusCodeSame(422);
    }

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
        $this->assertSame($payload['email'], $responseData['email']);
    }
}
