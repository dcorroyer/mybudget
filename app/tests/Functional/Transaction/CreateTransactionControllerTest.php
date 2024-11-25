<?php

declare(strict_types=1);

namespace App\Tests\Functional\Transaction;

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
#[Group('functional')]
#[Group('controller')]
#[Group('transaction')]
#[Group('transaction-controller')]
final class CreateTransactionControllerTest extends TestBase
{
    private const string API_BASE_ENDPOINT = '/api/accounts/';

    #[TestDox('When you call POST /api/accounts/{id}/transactions, it should create and return the transaction')]
    #[Test]
    public function createTransactionController_WhenDataOk_ReturnsTransaction(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $this->client->loginUser($user);

        $transactionPayload = [
            'description' => 'Test transaction',
            'amount' => 100,
            'type' => 'DEBIT',
            'date' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];

        // ACT
        $response = $this->clientRequest(
            Request::METHOD_POST,
            self::API_BASE_ENDPOINT . $account->getId() . '/transactions',
            $transactionPayload
        );
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertArrayHasKey('id', $responseData);
        self::assertSame($transactionPayload['description'], $responseData['description']);
        self::assertSame($transactionPayload['amount'], $responseData['amount']);
        self::assertSame($transactionPayload['type'], $responseData['type']);
    }
}
