<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Transaction|Account>
 */
class TransactionVoter extends Voter
{
    public const string VIEW = 'view';
    public const string EDIT = 'edit';
    public const string DELETE = 'delete';
    public const string CREATE = 'create';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute === self::CREATE) {
            return $subject instanceof Account;
        }

        if (\is_array($subject)) {
            return isset($subject['transaction'])
                && $subject['transaction'] instanceof Transaction;
        }

        return \in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], true)
            && $subject instanceof Transaction;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (! $user instanceof User) {
            return false;
        }

        if ($attribute === self::CREATE && $subject instanceof Account) {
            return $this->canCreate($subject, $user);
        }

        $transaction = \is_array($subject) ? $subject['transaction'] : $subject;
        $account = \is_array($subject) ? ($subject['account'] ?? null) : null;

        if (! $transaction instanceof Transaction) {
            return false;
        }

        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $this->canAccess($transaction, $user, $account),
            default => false,
        };
    }

    private function canAccess(Transaction $transaction, User $user, ?Account $requestedAccount = null): bool
    {
        $account = $transaction->getAccount();
        if ($account === null) {
            return false;
        }

        if ($requestedAccount !== null && $account->getId() !== $requestedAccount->getId()) {
            return false;
        }

        return $account->getUser() === $user
            && $user->getAccounts()->contains($account);
    }

    private function canCreate(Account $account, User $user): bool
    {
        return $account->getUser() === $user;
    }
}
