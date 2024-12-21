<?php

declare(strict_types=1);

namespace App\Tests\Functional\Account;

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
final class UpdateAccountControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/accounts';

    #[TestDox('When you call PUT /api/accounts/{id}, it should update and return the budget')]
    #[Test]
    public function updateAccountController_WhenDataOk_ReturnsAccount(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();

        $this->client->loginUser($user);

        $accountPayload = [
            'name' => 'Livret',
        ];

        // ACT
        $response = $this->clientRequest(
            Request::METHOD_PATCH,
            self::API_ENDPOINT . '/' . $account->getId(),
            $accountPayload
        );

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');
        self::assertSame('Livret', $response['name']);
    }
}
