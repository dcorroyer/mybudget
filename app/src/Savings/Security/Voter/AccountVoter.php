<?php

declare(strict_types=1);

namespace App\Savings\Security\Voter;

use App\Savings\Entity\Account;
use App\Shared\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Account>
 */
class AccountVoter extends Voter
{
    public const string VIEW = 'view';
    public const string EDIT = 'edit';
    public const string DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], true)
            && $subject instanceof Account;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (! $user instanceof User) {
            return false;
        }

        /** @var Account $account */
        $account = $subject;

        return $account->getUser() === $user;
    }
}
