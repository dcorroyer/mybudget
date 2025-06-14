<?php

declare(strict_types=1);

namespace App\Savings\Service;

use App\Savings\Dto\Payload\AccountPayload;
use App\Savings\Dto\Response\AccountResponse;
use App\Savings\Entity\Account;
use App\Savings\Exception\AccountNotFoundException;
use App\Savings\Repository\AccountRepository;
use App\Savings\Security\Voter\AccountVoter;
use App\Shared\Entity\User;
use App\Shared\Enum\ResourceTypesEnum;
use App\Shared\Exception\AbstractAccessDeniedException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccountService
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly Security $security,
    ) {
    }

    public function getExternal(int $id): AccountResponse
    {
        $account = $this->accountRepository->find($id);

        if ($account === null) {
            throw new AccountNotFoundException((string) $id);
        }

        if (! $this->authorizationChecker->isGranted(AccountVoter::VIEW, $account)) {
            throw new AbstractAccessDeniedException(ResourceTypesEnum::ACCOUNT->value);
        }

        return new AccountResponse(
            id: $account->getId(),
            name: $account->getName(),
            type: $account->getType()->value,
            balance: $account->getBalance()
        );
    }

    public function get(int $id): Account
    {
        $account = $this->accountRepository->find($id);

        if ($account === null) {
            throw new AccountNotFoundException((string) $id);
        }

        if (! $this->authorizationChecker->isGranted(AccountVoter::VIEW, $account)) {
            throw new AbstractAccessDeniedException(ResourceTypesEnum::ACCOUNT->value);
        }

        return $account;
    }

    public function create(AccountPayload $accountPayload): AccountResponse
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $account = new Account();

        $account->setName($accountPayload->name)
            ->setUser($user)
        ;

        $this->accountRepository->save($account, true);

        return new AccountResponse(
            id: $account->getId(),
            name: $account->getName(),
            type: $account->getType()->value,
            balance: $account->getBalance()
        );
    }

    public function update(AccountPayload $accountPayload, Account $account): AccountResponse
    {
        if (! $this->authorizationChecker->isGranted(AccountVoter::EDIT, $account)) {
            throw new AbstractAccessDeniedException(ResourceTypesEnum::ACCOUNT->value);
        }

        $account->setName($accountPayload->name);

        $this->accountRepository->save($account, true);

        return new AccountResponse(
            id: $account->getId(),
            name: $account->getName(),
            type: $account->getType()->value,
            balance: $account->getBalance()
        );
    }

    public function delete(Account $account): void
    {
        if (! $this->authorizationChecker->isGranted(AccountVoter::DELETE, $account)) {
            throw new AbstractAccessDeniedException(ResourceTypesEnum::ACCOUNT->value);
        }

        $this->accountRepository->delete($account, true);
    }

    /**
     * @return list<AccountResponse>
     */
    public function listExternal(): iterable
    {
        $accounts = $this->accountRepository->findBy([
            'user' => $this->security->getUser(),
        ]);

        $accountResponses = [];

        foreach ($accounts as $account) {
            $accountResponses[] = new AccountResponse(
                id: $account->getId(),
                name: $account->getName(),
                type: $account->getType()->value,
                balance: $account->getBalance()
            );
        }

        return $accountResponses;
    }

    /**
     * @return list<Account>
     */
    public function list(): iterable
    {
        return $this->accountRepository->findBy([
            'user' => $this->security->getUser(),
        ]);
    }

    public function save(Account $account, bool $flush = false): void
    {
        $this->accountRepository->save($account, $flush);
    }
}
