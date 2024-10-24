<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Account\Payload\AccountPayload;
use App\Entity\Account;
use App\Entity\User;
use App\Repository\AccountRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountService
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Security $security,
    ) {
    }

    public function get(int $id): Account
    {
        $account = $this->accountRepository->find($id);

        if ($account === null) {
            throw new NotFoundHttpException('Account not found');
        }

        $this->checkAccess($account);

        return $account;
    }

    public function create(AccountPayload $accountPayload): Account
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $account = new Account();

        $account->setName($accountPayload->name)
            ->setUser($user)
        ;

        $this->accountRepository->save($account, true);

        return $account;
    }

    public function update(AccountPayload $accountPayload, Account $account): Account
    {
        $this->checkAccess($account);

        $account->setName($accountPayload->name);

        $this->accountRepository->save($account, true);

        return $account;
    }

    public function delete(Account $account): void
    {
        $this->checkAccess($account);

        $this->accountRepository->delete($account, true);
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

    private function checkAccess(Account $account): void
    {
        if ($this->security->getUser() !== $account->getUser()) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }
}
