<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Account\Response\AccountPartialResponse;
use App\Dto\Common\PaginatedResponseDto;
use App\Dto\Common\PaginationMetaDto;
use App\Dto\Transaction\Payload\TransactionPayload;
use App\Dto\Transaction\Response\TransactionResponse;
use App\Entity\Transaction;
use App\Enum\ErrorMessagesEnum;
use App\Repository\TransactionRepository;
use App\Security\Voter\TransactionVoter;
use Doctrine\Common\Collections\Criteria;
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
        $transaction = $this->transactionRepository->find($id);

        if ($transaction === null) {
            throw new NotFoundHttpException(ErrorMessagesEnum::TRANSACTION_NOT_FOUND->value);
        }

        if (! $this->authorizationChecker->isGranted(TransactionVoter::VIEW, [
            'transaction' => $transaction,
            'account' => $account,
        ])) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        return $transaction;
    }

    public function create(int $accountId, TransactionPayload $transactionPayload): Transaction
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

        $this->balanceHistoryService->createBalanceHistoryEntry($transaction);

        return $transaction;
    }

    public function update(int $accountId, TransactionPayload $transactionPayload, Transaction $transaction): Transaction
    {
        $account = $this->accountService->get($accountId);

        if (! $this->authorizationChecker->isGranted(TransactionVoter::EDIT, [
            'transaction' => $transaction,
            'account' => $account,
        ])) {
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

    public function delete(int $accountId, Transaction $transaction): void
    {
        $account = $this->accountService->get($accountId);

        if (! $this->authorizationChecker->isGranted(TransactionVoter::VIEW, [
            'transaction' => $transaction,
            'account' => $account,
        ])) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $this->balanceHistoryService->deleteBalanceHistoryEntry($transaction);

        $this->transactionRepository->delete($transaction, true);
    }

    /**
     * @param int[]|null $accountIds
     * @param PaginationQueryParams|null $paginationQueryParams
     *
     * @return PaginatedResponseDto
     *
     * @throws \Exception
     */
    public function paginate(
        ?array $accountIds = null,
        ?PaginationQueryParams $paginationQueryParams = null
    ): PaginatedResponseDto {
        $criteria = Criteria::create();

        if (! empty($accountIds)) {
            $accounts = array_map(
                function (int $accountId) {
                    return $this->accountService->get($accountId);
                },
                $accountIds
            );
        } else {
            $accounts = $this->accountService->list();
        }

        $criteria->andWhere(Criteria::expr()?->in('account', $accounts));
        $criteria->orderBy([
            'date' => 'DESC',
        ]);

        $paginated = $this->transactionRepository->paginate($paginationQueryParams, null, $criteria);

        $transactions = [];

        foreach ($paginated->getItems() as $transaction) {
            $transactions[] = new TransactionResponse(
                id: $transaction->getId(),
                description: $transaction->getDescription(),
                amount: $transaction->getAmount(),
                type: $transaction->getType(),
                date: $transaction->getDate(),
                account: new AccountPartialResponse(
                    id: $transaction->getAccount()->getId(),
                    name: $transaction->getAccount()->getName(),
                ),
            );
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
}
