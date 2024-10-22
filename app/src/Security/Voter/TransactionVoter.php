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
        return \in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::CREATE], true)
            && ($subject instanceof Transaction || $subject instanceof Account);
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

        if (! $subject instanceof Transaction) {
            return false;
        }

        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $this->canAccess($subject, $user),
            default => false,
        };
    }

    private function canAccess(Transaction $transaction, User $user): bool
    {
        $account = $transaction->getAccount();
        if ($account === null) {
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
