<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Repository\TransactionRepository;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\TransactionFactory;
use App\Tests\Common\Factory\UserFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @internal
 */
#[Group('integration')]
#[Group('repository')]
#[Group('transaction')]
#[Group('transaction-repository')]
final class TransactionRepositoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    private TransactionRepository $transactionRepository;

    #[\Override]
    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::getContainer();
        $this->transactionRepository = $container->get(TransactionRepository::class);
    }

    #[TestDox('When finding all transactions from a date, it should return transactions after that date')]
    #[Test]
    public function findAllTransactionsFromDate_WhenDataOk_ReturnsTransactions(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $date = new \DateTime('2024-01-01');

        // Transaction avant la date (ne devrait pas être retournée)
        TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2023-12-31'),
        ]);

        // Transactions après la date (devraient être retournées)
        $transaction1 = TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-01'),
        ]);

        $transaction2 = TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-02'),
        ]);

        // ACT
        $transactions = $this->transactionRepository->findAllTransactionsFromDate($account, $date);

        // ASSERT
        self::assertCount(2, $transactions);
        $transactionIds = array_map(static fn ($transaction) => $transaction->getId(), $transactions);
        self::assertContains($transaction1->_real()->getId(), $transactionIds);
        self::assertContains($transaction2->_real()->getId(), $transactionIds);
    }

    #[TestDox('When finding all transactions from a date except one, it should return filtered transactions')]
    #[Test]
    public function findAllTransactionsFromDateExcept_WhenDataOk_ReturnsFilteredTransactions(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $date = new \DateTime('2024-01-01');

        // Transaction avant la date (ne devrait pas être retournée)
        TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2023-12-31'),
        ]);

        // Transaction à exclure (ne devrait pas être retournée)
        $excludedTransaction = TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-01'),
        ]);

        // Transaction à inclure (devrait être retournée)
        $includedTransaction = TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-02'),
        ]);

        // ACT
        $transactions = $this->transactionRepository->findAllTransactionsFromDateExcept(
            $account,
            $date,
            $excludedTransaction->_real()->getId()
        );

        // ASSERT
        self::assertCount(1, $transactions);
        self::assertSame($includedTransaction->_real()->getId(), $transactions[0]->getId());
    }

    #[TestDox('When finding transactions from a date, it should only return transactions for the specified account')]
    #[Test]
    public function findAllTransactionsFromDate_WithMultipleAccounts_ReturnsOnlySpecifiedAccountTransactions(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account1 = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $account2 = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $date = new \DateTime('2024-01-01');

        // Transactions pour le compte 1
        $transaction1 = TransactionFactory::createOne([
            'account' => $account1,
            'date' => new \DateTime('2024-01-01'),
        ]);

        // Transactions pour le compte 2 (ne devraient pas être retournées)
        TransactionFactory::createOne([
            'account' => $account2,
            'date' => new \DateTime('2024-01-01'),
        ]);

        // ACT
        $transactions = $this->transactionRepository->findAllTransactionsFromDate($account1, $date);

        // ASSERT
        self::assertCount(1, $transactions);
        self::assertSame($transaction1->_real()->getId(), $transactions[0]->getId());
    }

    #[TestDox('When finding transactions except one with invalid ID, it should return empty array')]
    #[Test]
    public function findAllTransactionsFromDateExcept_WithInvalidId_ReturnsEmptyArray(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $date = new \DateTime('2024-01-01');

        TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-01'),
        ]);

        // ACT
        $transactions = $this->transactionRepository->findAllTransactionsFromDateExcept(
            $account,
            $date,
            99999 // ID inexistant
        );

        // ASSERT
        self::assertCount(1, $transactions);
    }

    #[TestDox('When finding transactions with a future date, it should return empty array')]
    #[Test]
    public function findAllTransactionsFromDate_WithFutureDate_ReturnsEmptyArray(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $futureDate = new \DateTime('+1 year');

        TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('now'),
        ]);

        // ACT
        $transactions = $this->transactionRepository->findAllTransactionsFromDate($account, $futureDate);

        // ASSERT
        self::assertEmpty($transactions);
    }
}
