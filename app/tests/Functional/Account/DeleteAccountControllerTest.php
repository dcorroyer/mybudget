<?php

declare(strict_types=1);

namespace App\Tests\Functional\Budget;

use App\Tests\Common\Factory\AccountFactory;
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
#[Group('budget')]
#[Group('budget-controller')]
final class DeleteAccountControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/accounts';

    #[TestDox('When you call DELETE /api/accounts/{id}, it should delete the budget')]
    #[Test]
    public function deleteAccountController_WhenDataOk_ReturnsSuccessful(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $this->client->loginUser($user);

        $account = AccountFactory::createOne([
            'user' => $user,
        ]);

        // ACT
        $response = $this->clientRequest(Request::METHOD_DELETE, self::API_ENDPOINT . '/' . $account->getId());

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertSame(Response::HTTP_NO_CONTENT, $response);
    }
}
