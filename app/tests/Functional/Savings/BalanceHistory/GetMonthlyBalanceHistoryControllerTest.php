<?php

declare(strict_types=1);

namespace App\Tests\Functional\Savings\BalanceHistory;

use App\Savings\Enum\TransactionTypesEnum;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\TransactionFactory;
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
            'type' => TransactionTypesEnum::DEPOSIT,
            'date' => new \DateTime('2024-01-15'),
        ])->_real();

        $transaction2 = TransactionFactory::createOne([
            'account' => $account,
            'amount' => 500.0,
            'type' => TransactionTypesEnum::DEPOSIT,
            'date' => new \DateTime('2024-02-15'),
        ])->_real();

        // Les MonthlyBalance sont automatiquement créés via les événements de transactions

        // ACT
        $response = $this->clientRequest(Request::METHOD_GET, self::API_ENDPOINT);
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');

        // Vérification des comptes
        self::assertCount(1, $responseData['accounts']);
        self::assertSame($account->getId(), $responseData['accounts'][0]['id']);
        self::assertSame('Compte test', $responseData['accounts'][0]['name']);

        // Vérification des balances - la méthode fillMissingMonths ajoute tous les mois jusqu'à aujourd'hui
        self::assertGreaterThanOrEqual(2, \count($responseData['balances']));

        // Chercher les balances de janvier et février dans le tableau
        $januaryBalance = null;
        $februaryBalance = null;

        foreach ($responseData['balances'] as $balance) {
            if ($balance['date'] === '2024-01') {
                $januaryBalance = $balance;
            } elseif ($balance['date'] === '2024-02') {
                $februaryBalance = $balance;
            }
        }

        // Vérification de la balance de janvier
        self::assertNotNull($januaryBalance, 'January balance not found');
        self::assertSame(1000, $januaryBalance['balance']);

        // Vérification de la balance de février
        self::assertNotNull($februaryBalance, 'February balance not found');
        self::assertSame(1500, $februaryBalance['balance']);
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
            'type' => TransactionTypesEnum::DEPOSIT,
            'date' => new \DateTime('2024-01-15'),
        ])->_real();

        // MonthlyBalance sera créé automatiquement

        // Création des transactions pour le compte 2
        $transaction2 = TransactionFactory::createOne([
            'account' => $account2,
            'amount' => 500.0,
            'type' => TransactionTypesEnum::DEPOSIT,
            'date' => new \DateTime('2024-01-15'),
        ])->_real();

        // MonthlyBalance sera créé automatiquement

        // ACT
        $response = $this->clientRequest(
            Request::METHOD_GET,
            self::API_ENDPOINT . '?accountIds[]=' . $account1->getId()
        );
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');

        // Vérification des comptes (seulement compte 1)
        self::assertCount(1, $responseData['accounts']);
        self::assertSame($account1->getId(), $responseData['accounts'][0]['id']);
        self::assertSame('Compte 1', $responseData['accounts'][0]['name']);

        // Vérification des balances - la méthode fillMissingMonths ajoute tous les mois jusqu'à aujourd'hui
        self::assertGreaterThanOrEqual(1, \count($responseData['balances']));

        // Chercher la balance de janvier dans le tableau
        $januaryBalance = null;

        foreach ($responseData['balances'] as $balance) {
            if ($balance['date'] === '2024-01') {
                $januaryBalance = $balance;
                break;
            }
        }

        // Vérification de la balance de janvier
        self::assertNotNull($januaryBalance, 'January balance not found');
        self::assertSame(1001.1, $januaryBalance['balance']);
    }
}
