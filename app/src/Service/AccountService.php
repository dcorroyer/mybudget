<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Account\Payload\AccountPayload;
use App\Dto\Account\Response\AccountResponse;
use App\Entity\Account;
use App\Entity\User;
use App\Enum\ErrorMessagesEnum;
use App\Repository\AccountRepository;
use App\Security\Voter\AccountVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            throw new NotFoundHttpException(ErrorMessagesEnum::ACCOUNT_NOT_FOUND->value);
        }

        if (! $this->authorizationChecker->isGranted(AccountVoter::VIEW, $account)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
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
            throw new NotFoundHttpException(ErrorMessagesEnum::ACCOUNT_NOT_FOUND->value);
        }

        if (! $this->authorizationChecker->isGranted(AccountVoter::VIEW, $account)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
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
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
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
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
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
