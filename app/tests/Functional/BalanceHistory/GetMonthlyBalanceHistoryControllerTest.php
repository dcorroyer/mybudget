<?php

declare(strict_types=1);

namespace App\Tests\Functional\BalanceHistory;

use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\BalanceHistoryFactory;
use App\Tests\Common\Factory\TransactionFactory;
use App\Tests\Common\Factory\UserFactory;
use App\Tests\Functional\TestBase;
use App\Transaction\Enum\TransactionTypesEnum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
#[Group('functional')]
#[Group('controller')]
#[Group('balance-history')]
#[Group('balance-history-controller')]
final class GetMonthlyBalanceHistoryControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/accounts/balance-history';

    #[TestDox('When you call GET /api/accounts/balance-history, it should return the monthly balance history')]
    #[Test]
    public function getMonthlyBalanceHistory_WhenDataOk_ReturnsBalanceHistory(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $this->client->loginUser($user);

        $account = AccountFactory::createOne([
            'user' => $user,
            'name' => 'Compte test',
        ])->_real();

        $transaction1 = TransactionFactory::createOne([
            'account' => $account,
            'amount' => 1000.0,
            'type' => TransactionTypesEnum::CREDIT,
            'date' => new \DateTime('2024-01-15'),
        ])->_real();

        $transaction2 = TransactionFactory::createOne([
            'account' => $account,
            'amount' => 500.0,
            'type' => TransactionTypesEnum::CREDIT,
            'date' => new \DateTime('2024-02-15'),
        ])->_real();

        // Création des historiques de balance
        BalanceHistoryFactory::createOne([
            'account' => $account,
            'transaction' => $transaction1,
            'balanceBeforeTransaction' => 0.0,
            'balanceAfterTransaction' => 1000.0,
            'date' => new \DateTime('2024-01-15'),
        ]);

        BalanceHistoryFactory::createOne([
            'account' => $account,
            'transaction' => $transaction2,
            'balanceBeforeTransaction' => 1000.0,
            'balanceAfterTransaction' => 1500.0,
            'date' => new \DateTime('2024-02-15'),
        ]);

        // ACT
        $response = $this->clientRequest(Request::METHOD_GET, self::API_ENDPOINT);

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');

        // Vérification des comptes
        self::assertCount(1, $response['accounts']);
        self::assertSame($account->getId(), $response['accounts'][0]['id']);
        self::assertSame('Compte test', $response['accounts'][0]['name']);

        // Vérification des balances
        self::assertCount(2, $response['balances']);

        // Vérification de la balance de janvier
        self::assertSame('2024-01', $response['balances'][0]['date']);
        self::assertSame(1000, $response['balances'][0]['balance']);

        // Vérification de la balance de février
        self::assertSame('2024-02', $response['balances'][1]['date']);
        self::assertSame(1500, $response['balances'][1]['balance']);
    }

    #[TestDox(
        'When you call GET /api/accounts/balance-history with account filter, it should return filtered balance history'
    )]
    #[Test]
    public function getMonthlyBalanceHistory_WithAccountFilter_ReturnsFilteredBalanceHistory(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $this->client->loginUser($user);

        // Création de deux comptes
        $account1 = AccountFactory::createOne([
            'user' => $user,
            'name' => 'Compte 1',
        ])->_real();

        $account2 = AccountFactory::createOne([
            'user' => $user,
            'name' => 'Compte 2',
        ])->_real();

        // Création des transactions et historiques pour le compte 1
        $transaction1 = TransactionFactory::createOne([
            'account' => $account1,
            'amount' => 1001.10,
            'type' => TransactionTypesEnum::CREDIT,
            'date' => new \DateTime('2024-01-15'),
        ])->_real();

        BalanceHistoryFactory::createOne([
            'account' => $account1,
            'transaction' => $transaction1,
            'balanceBeforeTransaction' => 0.0,
            'balanceAfterTransaction' => 1001.10,
            'date' => new \DateTime('2024-01-15'),
        ]);

        // Création des transactions et historiques pour le compte 2
        $transaction2 = TransactionFactory::createOne([
            'account' => $account2,
            'amount' => 500.0,
            'type' => TransactionTypesEnum::CREDIT,
            'date' => new \DateTime('2024-01-15'),
        ])->_real();

        BalanceHistoryFactory::createOne([
            'account' => $account2,
            'transaction' => $transaction2,
            'balanceBeforeTransaction' => 0.0,
            'balanceAfterTransaction' => 500.0,
            'date' => new \DateTime('2024-01-15'),
        ]);

        // ACT
        $response = $this->clientRequest(
            Request::METHOD_GET,
            self::API_ENDPOINT . '?accountIds[]=' . $account1->getId()
        );

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');

        // Vérification des comptes (seulement compte 1)
        self::assertCount(1, $response['accounts']);
        self::assertSame($account1->getId(), $response['accounts'][0]['id']);
        self::assertSame('Compte 1', $response['accounts'][0]['name']);

        // Vérification des balances
        self::assertCount(1, $response['balances']);
        self::assertSame('2024-01', $response['balances'][0]['date']);
        self::assertSame(1001.10, $response['balances'][0]['balance']);
    }
}
