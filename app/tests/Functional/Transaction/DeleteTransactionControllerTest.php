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
#[Group('integration')]
#[Group('controller')]
#[Group('transaction')]
#[Group('transaction-controller')]
final class DeleteTransactionControllerTest extends TestBase
{
    private const string API_BASE_ENDPOINT = '/api/accounts/';

    #[TestDox('When you call DELETE /api/transactions/{id}, it should delete the transaction')]
    #[Test]
    public function deleteTransactionController_WhenDataOk_ReturnsNoContent(): void
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
        $this->clientRequest(
            Request::METHOD_DELETE,
            self::API_BASE_ENDPOINT . $account->getId() . '/transactions/' . $transaction->getId()
        );

        // ASSERT
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
