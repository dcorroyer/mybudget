<?php

declare(strict_types=1);

namespace App\Tests\Functional\Transaction;

use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\TransactionFactory;
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
#[Group('functional')]
#[Group('controller')]
#[Group('transaction')]
#[Group('transaction-controller')]
final class GetTransactionControllerTest extends TestBase
{
    private const string API_BASE_ENDPOINT = '/api/accounts/';

    #[TestDox('When you call GET /api/accounts/{id}transactions/{id}, it should return the transaction')]
    #[Test]
    public function getTransactionController_WhenDataOk_ReturnsTransaction(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ])->_real();
        $this->client->loginUser($user);

        // ACT
        $response = $this->clientRequest(
            Request::METHOD_GET,
            self::API_BASE_ENDPOINT . $account->getId() . '/transactions/' . $transaction->getId()
        );
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSame($transaction->getId(), $responseData['id']);
        self::assertSame($transaction->getDescription(), $responseData['description']);
    }
}
