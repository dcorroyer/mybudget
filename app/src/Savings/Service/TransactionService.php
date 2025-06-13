<?php

declare(strict_types=1);

namespace App\Savings\Service;

use App\Savings\Dto\Payload\TransactionPayload;
use App\Savings\Dto\Response\AccountPartialResponse;
use App\Savings\Dto\Response\TransactionResponse;
use App\Savings\Entity\Account;
use App\Savings\Entity\Transaction;
use App\Savings\Exception\TransactionNotFoundException;
use App\Savings\Repository\TransactionRepository;
use App\Savings\Security\Voter\TransactionVoter;
use App\Shared\Dto\PaginatedResponseDto;
use App\Shared\Dto\PaginationMetaDto;
use App\Shared\Dto\PaginationQueryParams;
use App\Shared\Enum\ResourceTypesEnum;
use App\Shared\Exception\AbstractAccessDeniedException;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly AccountService $accountService,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function get(int $accountId, int $id): TransactionResponse
    {
        $account = $this->accountService->get($accountId);
        $transaction = $this->transactionRepository->find($id);

        if ($transaction === null) {
            throw new TransactionNotFoundException((string) $id);
        }

        if (! $this->authorizationChecker->isGranted(TransactionVoter::VIEW, [
            'transaction' => $transaction,
            'account' => $account,
        ])) {
            throw new AbstractAccessDeniedException(ResourceTypesEnum::TRANSACTION->value);
        }

        return $this->createTransactionResponse($transaction);
    }

    public function create(int $accountId, TransactionPayload $transactionPayload): TransactionResponse
    {
        $account = $this->accountService->get($accountId);

        $transaction = (new Transaction())
            ->setDescription($transactionPayload->description)
            ->setAmount($transactionPayload->amount)
            ->setType($transactionPayload->type)
            ->setDate($transactionPayload->date)
            ->setAccount($account)
        ;

        $this->transactionRepository->save($transaction, true);

        return $this->createTransactionResponse($transaction);
    }

    public function update(
        int $accountId,
        TransactionPayload $transactionPayload,
        Transaction $transaction
    ): TransactionResponse {
        $account = $this->accountService->get($accountId);

        if (! $this->authorizationChecker->isGranted(TransactionVoter::EDIT, [
            'transaction' => $transaction,
            'account' => $account,
        ])) {
            throw new AbstractAccessDeniedException(ResourceTypesEnum::TRANSACTION->value);
        }

        $transaction->setDescription($transactionPayload->description)
            ->setAmount($transactionPayload->amount)
            ->setType($transactionPayload->type)
            ->setDate($transactionPayload->date)
        ;

        $this->transactionRepository->save($transaction, true);

        return $this->createTransactionResponse($transaction);
    }

    public function delete(int $accountId, Transaction $transaction): void
    {
        $account = $this->accountService->get($accountId);

        if (! $this->authorizationChecker->isGranted(TransactionVoter::DELETE, [
            'transaction' => $transaction,
            'account' => $account,
        ])) {
            throw new AbstractAccessDeniedException(ResourceTypesEnum::TRANSACTION->value);
        }

        $this->transactionRepository->delete($transaction, true);
    }

    /**
     * @return array<Transaction> $transactions
     */
    public function getAllTransactionsFromDate(Account $account, \DateTimeInterface $fromDate): array
    {
        return $this->transactionRepository->findAllTransactionsFromDate($account, $fromDate);
    }

    /**
     * @return array<Transaction> $transactions
     */
    public function getAllTransactionsFromDateExcept(
        Account $account,
        \DateTimeInterface $fromDate,
        int $excludedTransactionId
    ): array {
        return $this->transactionRepository->findAllTransactionsFromDateExcept(
            $account,
            $fromDate,
            $excludedTransactionId
        );
    }

    /**
     * @param int[]|null $accountIds
     */
    public function paginate(
        ?array $accountIds = null,
        ?PaginationQueryParams $paginationQueryParams = null
    ): PaginatedResponseDto {
        $criteria = Criteria::create();

        if (! empty($accountIds)) {
            $accounts = [];
            foreach ($accountIds as $accountId) {
                $accounts[] = $this->accountService->get($accountId);
            }
        } else {
            $accounts = $this->accountService->list();
        }

        $criteria->andWhere(Criteria::expr()->in('account', $accounts));
        $criteria->orderBy([
            'date' => 'DESC',
        ]);

        $paginated = $this->transactionRepository->paginate($paginationQueryParams, null, $criteria);

        $transactions = [];

        /** @var Transaction $transaction */
        foreach ($paginated->getItems() as $transaction) {
            $transactions[] = $this->createTransactionResponse($transaction);
        }

        return new PaginatedResponseDto(
            data: $transactions,
            meta: new PaginationMetaDto(
                total: $paginated->getTotalItemCount(),
                page: $paginated->getCurrentPageNumber(),
                limit: $paginated->getItemNumberPerPage(),
            ),
        );
    }

    private function createTransactionResponse(Transaction $transaction): TransactionResponse
    {
        /** @var Account $account */
        $account = $transaction->getAccount();

        return new TransactionResponse(
            id: $transaction->getId(),
            description: $transaction->getDescription(),
            amount: $transaction->getAmount(),
            type: $transaction->getType(),
            date: $transaction->getDate(),
            account: new AccountPartialResponse(id: $account->getId(), name: $account->getName()),
        );
    }
}
