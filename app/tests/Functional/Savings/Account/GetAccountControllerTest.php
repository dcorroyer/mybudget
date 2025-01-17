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
final class GetAccountControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/accounts';

    #[TestDox('When you call GET /api/accounts/{id}, it should returns the budget')]
    #[Test]
    public function getAccountController_WhenDataOk_ReturnsAccount(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $this->client->loginUser($user);

        $account = AccountFactory::createOne([
            'user' => $user,
        ]);

        // ACT
        $response = $this->clientRequest(Request::METHOD_GET, self::API_ENDPOINT . '/' . $account->getId());
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertSame($account->getId(), $responseData['id']);
    }
}
