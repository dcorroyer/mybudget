<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Transaction\Payload\TransactionPayload;
use App\Entity\Transaction;
use App\Enum\ErrorMessagesEnum;
use App\Enum\TransactionTypesEnum;
use App\Repository\TransactionRepository;
use App\Service\AccountService;
use App\Service\BalanceHistoryService;
use App\Service\TransactionService;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\TransactionFactory;
use App\Tests\Common\Factory\UserFactory;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;
use My\RestBundle\Test\Helper\PaginationTestHelper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('service')]
#[Group('transaction')]
#[Group('transaction-service')]
final class TransactionServiceTest extends TestCase
{
    use Factories;

    private TransactionRepository $transactionRepository;
    private AccountService $accountService;
    private AuthorizationCheckerInterface $authorizationChecker;
    private TransactionService $transactionService;
    private BalanceHistoryService $balanceHistoryService;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->accountService = $this->createMock(AccountService::class);
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->balanceHistoryService = $this->createMock(BalanceHistoryService::class);

        $this->transactionService = new TransactionService(
            transactionRepository: $this->transactionRepository,
            accountService: $this->accountService,
            authorizationChecker: $this->authorizationChecker,
            balanceHistoryService: $this->balanceHistoryService,
        );
    }

    #[TestDox('When calling get transaction, it should get the transaction')]
    #[Test]
    public function getTransactionService_WhenDataOk_ReturnsTransaction(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $transaction = TransactionFactory::createOne([
            'id' => 1,
            'account' => $account,
        ]);

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->willReturn(true)
        ;

        $this->accountService->expects($this->once())
            ->method('get')
            ->willReturn($account)
        ;

        $this->transactionRepository->expects($this->once())
            ->method('find')
            ->willReturn($transaction)
        ;

        // ACT
        $transactionResponse = $this->transactionService->get($account->getId(), $transaction->getId());

        // ASSERT
        self::assertSame($transaction->getId(), $transactionResponse->getId());
    }

    #[TestDox('When calling get transaction with bad id, it should returns not found exception')]
    #[Test]
    public function getTransactionService_WithBadId_ReturnsNotFoundException(): void
    {
        // ARRANGE
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(ErrorMessagesEnum::TRANSACTION_NOT_FOUND->value);

        // ACT
        $this->transactionService->get(1, 1);
    }

    #[TestDox('When calling get transaction for another user, it should returns access denied exception')]
    #[Test]
    public function getTransactionService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ARRANGE
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage(ErrorMessagesEnum::ACCESS_DENIED->value);

        // ARRANGE
        $transaction = TransactionFactory::createOne();

        $this->transactionRepository->expects($this->once())
            ->method('find')
            ->willReturn($transaction)
        ;

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->willReturn(false)
        ;

        // ACT
        $this->transactionService->get($transaction->getAccount()->getId(), $transaction->getId());
    }

    #[TestDox('When calling create transaction, it should return the transaction created')]
    #[Test]
    public function createTransactionService_WhenDataOk_ReturnsTransactionCreated(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $transactionPayload = new TransactionPayload();
        $transactionPayload->description = 'Test transaction';
        $transactionPayload->amount = 100;
        $transactionPayload->type = TransactionTypesEnum::DEBIT;
        $transactionPayload->date = new \DateTime();

        $this->accountService->expects($this->once())
            ->method('get')
            ->willReturn($account)
        ;

        $this->transactionRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Transaction $transaction) {
                $transaction->setId(1);
            })
        ;

        // ACT
        $transactionResponse = $this->transactionService->create($account->getId(), $transactionPayload);

        // ASSERT
        self::assertInstanceOf(Transaction::class, $transactionResponse);
        self::assertSame(1, $transactionResponse->getId());
        self::assertSame('Test transaction', $transactionResponse->getDescription());
    }

    #[TestDox('When calling create transaction with non-existent account, it should throw not found exception')]
    #[Test]
    public function createTransactionService_WithNonExistentAccount_ReturnsNotFoundException(): void
    {
        // ASSERT
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(ErrorMessagesEnum::ACCOUNT_NOT_FOUND->value);

        // ARRANGE
        $transactionPayload = new TransactionPayload();

        $this->accountService->expects($this->once())
            ->method('get')
            ->will($this->throwException(new NotFoundHttpException('Account not found')))
        ;

        // ACT
        $this->transactionService->create(999, $transactionPayload);
    }

    #[TestDox('When calling update transaction, it should update and return the transaction updated')]
    #[Test]
    public function updateTransactionService_WhenDataOk_ReturnsTransactionUpdated(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $transaction = TransactionFactory::createOne([
            'id' => 1,
            'account' => $account,
        ]);

        $transactionPayload = new TransactionPayload();
        $transactionPayload->description = 'Updated transaction';
        $transactionPayload->amount = 200.00;
        $transactionPayload->type = TransactionTypesEnum::DEBIT;
        $transactionPayload->date = new \DateTime();

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->willReturn(true)
        ;

        $this->transactionRepository->expects($this->once())
            ->method('save')
            ->with($transaction, true)
        ;

        // ACT
        $transactionResponse = $this->transactionService->update(
            $transaction->getAccount()->getId(),
            $transactionPayload,
            $transaction
        );

        // ASSERT
        self::assertInstanceOf(Transaction::class, $transactionResponse);
        self::assertSame('Updated transaction', $transactionResponse->getDescription());
        self::assertSame(200.00, $transactionResponse->getAmount());
    }

    #[TestDox('When calling update transaction with bad user, it should return access denied exception')]
    #[Test]
    public function updateTransactionService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage(ErrorMessagesEnum::ACCESS_DENIED->value);

        // ARRANGE
        $transaction = TransactionFactory::createOne();
        $transactionPayload = new TransactionPayload();

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->willReturn(false)
        ;

        // ACT
        $this->transactionService->update($transaction->getAccount()->getId(), $transactionPayload, $transaction);
    }

    #[TestDox('When calling delete transaction, it should delete the transaction')]
    #[Test]
    public function deleteTransactionService_WhenDataOk_ReturnsNoContent(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->willReturn(true)
        ;

        $this->transactionRepository->expects($this->once())
            ->method('delete')
            ->with($transaction, true)
        ;

        // ACT
        $this->transactionService->delete($transaction->getAccount()->getId(), $transaction);

        // ASSERT
        self::assertInstanceOf(Transaction::class, $transaction);
    }

    #[TestDox('When calling delete transaction with bad user, it should return access denied exception')]
    #[Test]
    public function deleteTransactionService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage(ErrorMessagesEnum::ACCESS_DENIED->value);

        // ARRANGE
        $transaction = TransactionFactory::createOne();

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->willReturn(false)
        ;

        // ACT
        $this->transactionService->delete($transaction->getAccount()->getId(), $transaction);
    }

    #[TestDox('When you call list, it should return the transactions list')]
    #[Test]
    public function listTransactionService_WhenDataOk_ReturnsTransactionsList(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $transactions = TransactionFactory::createMany(3, [
            'account' => $account,
        ]);
        $slidingPagination = PaginationTestHelper::getPagination($transactions);

        $this->accountService->expects($this->once())
            ->method('get')
            ->willReturn($account)
        ;

        $this->transactionRepository->method('paginate')
            ->willReturn($slidingPagination)
        ;

        // ACT
        $transactionsResponse = $this->transactionService->paginate([$account->getId()], new PaginationQueryParams());

        // ASSERT
        self::assertCount(\count($transactions), $transactionsResponse->data);
    }
}
