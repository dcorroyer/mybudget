<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Transaction\Payload\TransactionPayload;
use App\Entity\Transaction;
use App\Enum\ErrorMessagesEnum;
use App\Repository\TransactionRepository;
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
    ) {
    }

    public function get(int $id): Transaction
    {
        $transaction = $this->transactionRepository->find($id);

        if ($transaction === null) {
            throw new NotFoundHttpException(ErrorMessagesEnum::TRANSACTION_NOT_FOUND->value);
        }

        if (! $this->authorizationChecker->isGranted('view', $transaction)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        return $transaction;
    }

    public function create(int $accountId, TransactionPayload $transactionPayload): Transaction
    {
        $account = $this->accountService->get($accountId);

        if (! $this->authorizationChecker->isGranted('create', $account)) {
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

        return $transaction;
    }

    public function update(TransactionPayload $transactionPayload, Transaction $transaction): Transaction
    {
        if (! $this->authorizationChecker->isGranted('edit', $transaction)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $transaction->setDescription($transactionPayload->description)
            ->setAmount($transactionPayload->amount)
            ->setType($transactionPayload->type)
            ->setDate($transactionPayload->date)
        ;

        $this->transactionRepository->save($transaction, true);

        return $transaction;
    }

    public function delete(Transaction $transaction): void
    {
        if (! $this->authorizationChecker->isGranted('delete', $transaction)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $this->transactionRepository->delete($transaction, true);
    }

    /**
     * @return SlidingPagination<int, Transaction>
     */
    public function paginate(int $accountId, ?PaginationQueryParams $paginationQueryParams = null): SlidingPagination
    {
        $account = $this->accountService->get($accountId);

        if (! $this->authorizationChecker->isGranted('view', $account)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->eq('account', $account))
            ->orderBy([
                'date' => 'DESC',
            ])
        ;

        return $this->transactionRepository->paginate($paginationQueryParams, null, $criteria);
    }
}
