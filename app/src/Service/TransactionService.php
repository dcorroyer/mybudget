<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Transaction\Payload\TransactionPayload;
use App\Entity\Transaction;
use App\Enum\ErrorMessagesEnum;
use App\Repository\TransactionRepository;
use App\Security\Voter\AccountVoter;
use App\Security\Voter\TransactionVoter;
use Doctrine\Common\Collections\Criteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly AccountService $accountService,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly BalanceHistoryService $balanceHistoryService,
    ) {
    }

    public function get(int $accountId, int $id): Transaction
    {
        $account = $this->accountService->get($accountId);

        if (! $this->authorizationChecker->isGranted(AccountVoter::VIEW, $account)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $transaction = $this->transactionRepository->find($id);

        if ($transaction === null) {
            throw new NotFoundHttpException(ErrorMessagesEnum::TRANSACTION_NOT_FOUND->value);
        }

        if (! $this->authorizationChecker->isGranted(TransactionVoter::VIEW, $transaction)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        return $transaction;
    }

    public function create(int $accountId, TransactionPayload $transactionPayload): Transaction
    {
        $account = $this->accountService->get($accountId);

        if (! $this->authorizationChecker->isGranted(TransactionVoter::CREATE, $account)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $transaction = (new Transaction())
            ->setDescription($transactionPayload->description)
            ->setAmount($transactionPayload->amount)
            ->setType($transactionPayload->type)
            ->setDate($transactionPayload->date)
            ->setAccount($account)
        ;

        $this->transactionRepository->save($transaction, true);

        $this->balanceHistoryService->createBalanceHistoryEntry($transaction);

        return $transaction;
    }

    public function update(TransactionPayload $transactionPayload, Transaction $transaction): Transaction
    {
        if (! $this->authorizationChecker->isGranted(TransactionVoter::EDIT, $transaction)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $transaction->setDescription($transactionPayload->description)
            ->setAmount($transactionPayload->amount)
            ->setType($transactionPayload->type)
            ->setDate($transactionPayload->date)
        ;

        $this->transactionRepository->save($transaction, true);

        $this->balanceHistoryService->updateBalanceHistoryEntry($transaction);

        return $transaction;
    }

    public function delete(Transaction $transaction): void
    {
        if (! $this->authorizationChecker->isGranted(TransactionVoter::DELETE, $transaction)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $this->balanceHistoryService->deleteBalanceHistoryEntry($transaction);

        $this->transactionRepository->delete($transaction, true);
    }

    /**
     * @param int[]|null $accountIds
     *
     * @return SlidingPagination<int, Transaction>
     */
    public function paginate(
        ?array $accountIds = null,
        ?PaginationQueryParams $paginationQueryParams = null
    ): SlidingPagination {
        $criteria = Criteria::create();

        if (! empty($accountIds)) {
            $accounts = array_map(
                function (int $accountId) {
                    $account = $this->accountService->get($accountId);
                    if (! $this->authorizationChecker->isGranted(AccountVoter::VIEW, $account)) {
                        throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
                    }

                    return $account;
                },
                $accountIds
            );
        } else {
            $accounts = $this->accountService->list();

            if (empty($accounts)) {
                return $this->transactionRepository->paginate($paginationQueryParams, null, $criteria);
            }
        }

        $criteria->andWhere(Criteria::expr()?->in('account', $accounts));
        $criteria->orderBy([
            'date' => 'DESC',
        ]);

        return $this->transactionRepository->paginate($paginationQueryParams, null, $criteria);
    }
}
