<?php

declare(strict_types=1);

namespace App\Transaction\Security\Voter;

use App\Account\Entity\Account;
use App\Transaction\Entity\Transaction;
use App\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, array{transaction: Transaction, account: Account}>
 */
class TransactionVoter extends Voter
{
    public const string VIEW = 'view';
    public const string EDIT = 'edit';
    public const string DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], true)
            && \is_array($subject)
            && isset($subject['transaction'], $subject['account'])
            && $subject['transaction'] instanceof Transaction
            && $subject['account'] instanceof Account;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (! $user instanceof User) {
            return false;
        }

        /** @var array{transaction: Transaction, account: Account} $subject */
        $transaction = $subject['transaction'];
        $account = $subject['account'];

        if ($transaction->getAccount() !== $account) {
            return false;
        }

        return $account->getUser() === $user;
    }
}
