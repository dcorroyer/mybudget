<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Savings\Dto\Payload\AccountPayload;
use App\Savings\Dto\Response\AccountResponse;
use App\Savings\Entity\Account;
use App\Savings\Exception\AccountNotFoundException;
use App\Savings\Repository\AccountRepository;
use App\Savings\Service\AccountService;
use App\Shared\Exception\AbstractAccessDeniedException;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\UserFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('service')]
#[Group('account')]
#[Group('account-service')]
final class AccountServiceTest extends TestCase
{
    use Factories;

    private AccountRepository $accountRepository;

    private Security $security;

    private AccountService $accountService;

    private AuthorizationCheckerInterface $authorizationChecker;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->security = $this->createMock(Security::class);

        $this->accountService = new AccountService(
            accountRepository: $this->accountRepository,
            authorizationChecker: $this->authorizationChecker,
            security: $this->security,
        );
    }

    #[TestDox('When calling create account, it should return the budget created')]
    #[Test]
    public function createAccountService_WhenDataOk_ReturnsBudgetCreated(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne([
            'id' => 1,
            'user' => $this->security->getUser(),
        ]);

        $accountPayload = (new AccountPayload());
        $accountPayload->name = 'Livret';

        $this->accountRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Account $account): void {
                $account->setId(1)
                    ->setName('Livret')
                ;
            })
        ;

        // ACT
        $accountResponse = $this->accountService->create($accountPayload);

        // ASSERT
        self::assertInstanceOf(Account::class, $account);
        self::assertSame($account->getId(), $accountResponse->id);
        self::assertSame('Livret', $accountResponse->name);
    }

    #[TestDox('When calling update account, it should update and return the account updated')]
    #[Test]
    public function updateAccountService_WhenDataOk_ReturnsAccountUpdated(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();

        $account = AccountFactory::createOne([
            'id' => 1,
            'user' => $user,
        ]);

        $accountPayload = (new AccountPayload());
        $accountPayload->name = 'Livret updated';

        $this->security->expects($this->any())
            ->method('getUser')
            ->willReturn($user)
        ;

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with('edit', $account)
            ->willReturn(true)
        ;

        $this->accountRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Account $account): void {
                $account->setId(1)
                    ->setName('Livret updated')
                ;
            })
        ;

        // ACT
        $accountResponse = $this->accountService->update($accountPayload, $account);

        // ASSERT
        self::assertInstanceOf(AccountResponse::class, $accountResponse);
        self::assertSame($account->getId(), $accountResponse->id);
        self::assertSame('Livret updated', $accountResponse->name);
    }

    #[TestDox('When calling update account with bad user, it should returns access denied exception')]
    #[Test]
    public function updateAccountService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AbstractAccessDeniedException::class);
        // ARRANGE
        $account = AccountFactory::createOne([
            'id' => 1,
        ]);

        $accountPayload = (new AccountPayload());
        $accountPayload->name = 'Livret updated';

        // ACT
        $this->accountService->update($accountPayload, $account);
    }

    #[TestDox('When calling get account, it should get the account')]
    #[Test]
    public function getAccountService_WhenDataOk_ReturnsAccount(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();

        $account = AccountFactory::createOne([
            'id' => 1,
            'user' => $user,
        ]);

        $this->security->expects($this->any())
            ->method('getUser')
            ->willReturn($user)
        ;

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with('view', $account)
            ->willReturn(true)
        ;

        $this->accountRepository->expects($this->once())
            ->method('find')
            ->willReturn($account)
        ;

        // ACT
        $accountResponse = $this->accountService->get($account->getId());

        // ASSERT
        self::assertInstanceOf(Account::class, $accountResponse);
        self::assertSame($account->getId(), $accountResponse->getId());
    }

    #[TestDox('When calling get account with bad id, it should throw not found exception')]
    #[Test]
    public function getAccountService_WithBadId_ReturnsNotFoundException(): void
    {
        // ASSERT
        $this->expectException(AccountNotFoundException::class);

        // ACT
        $this->accountService->get(999);
    }

    #[TestDox('When calling get account for another user, it should throw access denied exception')]
    #[Test]
    public function getAccountService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AbstractAccessDeniedException::class);

        // ARRANGE
        $account = AccountFactory::new()->withoutPersisting()->create();

        $this->accountRepository->expects($this->once())
            ->method('find')
            ->willReturn($account)
        ;

        // ACT
        $this->accountService->get($account->getId());
    }

    #[TestDox('When calling delete account, it should delete the account')]
    #[Test]
    public function deleteAccountService_WhenDataOk_ReturnsNoContent(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();

        $account = AccountFactory::createOne([
            'user' => $user,
        ]);

        $this->security->expects($this->any())
            ->method('getUser')
            ->willReturn($user)
        ;

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with('delete', $account)
            ->willReturn(true)
        ;

        $this->accountRepository->expects($this->once())
            ->method('delete')
            ->with($account, true)
        ;

        // ACT
        $this->accountService->delete($account);

        // ASSERT
        self::assertInstanceOf(Account::class, $account);
    }

    #[TestDox('When calling delete account with bad user, it should returns access denied exception')]
    #[Test]
    public function deleteAccountService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AbstractAccessDeniedException::class);
        // ARRANGE
        $account = AccountFactory::createOne();

        // ACT
        $this->accountService->delete($account);
    }

    #[TestDox('When you call list, it should return the accounts list')]
    #[Test]
    public function listAccountService_WhenDataOk_ReturnsAccountsList(): void
    {
        // ARRANGE
        AccountFactory::createMany(3);

        $accounts = AccountFactory::createMany(3, [
            'user' => $this->security->getUser(),
        ]);

        $this->accountRepository->method('findBy')
            ->willReturn($accounts)
        ;

        // ACT
        $accountsResponse = $this->accountService->list();

        // ASSERT
        self::assertCount(\count($accounts), $accountsResponse);
    }
}
