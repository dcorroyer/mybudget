<?php

declare(strict_types=1);

namespace App\Tests\Functional\Transaction;

use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\TransactionFactory;
use App\Tests\Common\Factory\UserFactory;
use App\Tests\Functional\TestBase;
use App\Transaction\Enum\TransactionTypesEnum;
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
final class UpdateTransactionControllerTest extends TestBase
{
    private const string API_BASE_ENDPOINT = '/api/accounts/';

    #[TestDox('When you call PUT /api/accounts/{id}/transactions/{id}, it should update and return the transaction')]
    #[Test]
    public function updateTransactionController_WhenDataOk_ReturnsUpdatedTransaction(): void
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

        $updatePayload = [
            'description' => 'Updated transaction',
            'amount' => 200,
            'type' => TransactionTypesEnum::DEBIT,
            'date' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];

        // ACT
        $response = $this->clientRequest(
            Request::METHOD_PUT,
            self::API_BASE_ENDPOINT . $account->getId() . '/transactions/' . $transaction->getId(),
            $updatePayload
        );

        // ASSERT
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSame($transaction->getId(), $response['id']);
        self::assertSame($updatePayload['description'], $response['description']);
        self::assertSame($updatePayload['amount'], $response['amount']);
    }
}
