<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Transaction\Payload\TransactionPayload;
use App\Entity\Transaction;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\Criteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly AccountRepository $accountRepository,
        private readonly Security $security,
    ) {
    }

    public function get(int $id): Transaction
    {
        $transaction = $this->transactionRepository->find($id);

        if ($transaction === null) {
            throw new NotFoundHttpException('Transaction not found');
        }

        return $transaction;
    }

    public function create(TransactionPayload $transactionPayload): Transaction
    {
        $account = $this->accountRepository->find($transactionPayload->accountId);

        if ($account === null) {
            throw new NotFoundHttpException('Account not found');
        }

        $transaction = new Transaction();

        $transaction->setDescription($transactionPayload->description)
            ->setAmount($transactionPayload->amount)
            ->setType($transactionPayload->type)
            ->setDate($transactionPayload->date)
            ->setAccount($account);

        $this->transactionRepository->save($transaction, true);

        return $transaction;
    }

    public function update(TransactionPayload $transactionPayload, Transaction $transaction): Transaction
    {
        $transaction->setDescription($transactionPayload->description)
            ->setAmount($transactionPayload->amount)
            ->setType($transactionPayload->type)
            ->setDate($transactionPayload->date);

        if ($transaction->getAccount()->getId() !== $transactionPayload->accountId) {
            $newAccount = $this->accountRepository->find($transactionPayload->accountId);
            if ($newAccount === null) {
                throw new NotFoundHttpException('New account not found');
            }
            $transaction->setAccount($newAccount);
        }

        $this->transactionRepository->save($transaction, true);

        return $transaction;
    }

    public function delete(Transaction $transaction): void
    {
        $this->transactionRepository->delete($transaction, true);
    }

    /**
     * @return SlidingPagination<int, Transaction>
     */
    public function list(
        ?PaginationQueryParams $paginationQueryParams = null
    ): SlidingPagination {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->in('account', $this->accountRepository->findBy(['user' => $this->security->getUser()])))
            ->orderBy([
                'date' => 'DESC',
            ])
        ;

        return $this->transactionRepository->paginate($paginationQueryParams, null, $criteria);
    }
}
