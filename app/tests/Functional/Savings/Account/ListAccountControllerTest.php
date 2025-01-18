<?php

declare(strict_types=1);

namespace App\Tests\Functional\Savings\Account;

use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\UserFactory;
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
#[Group('account')]
#[Group('account-controller')]
final class ListAccountControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/accounts';

    #[TestDox('When you call GET /api/accounts, it should returns the accounts list')]
    #[Test]
    public function listAccountController_WhenDataOk_ReturnsAccountsList(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $this->client->loginUser($user);

        $accounts = AccountFactory::createMany(3, [
            'user' => $user,
        ]);

        // ACT
        $response = $this->clientRequest(Request::METHOD_GET, self::API_ENDPOINT);
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertCount(\count($accounts), $responseData);
    }
}
