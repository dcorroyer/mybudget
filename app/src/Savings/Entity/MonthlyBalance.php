<?php

declare(strict_types=1);

namespace App\Savings\Entity;

use App\Savings\Repository\MonthlyBalanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonthlyBalanceRepository::class)]
#[ORM\Table(name: '`monthly_balance`')]
#[ORM\UniqueConstraint(name: 'unique_account_month', columns: ['account_id', 'month'])]
class MonthlyBalance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Account $account;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $month;

    #[ORM\Column(type: Types::FLOAT)]
    private float $endOfMonthBalance = 0.0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $lastUpdated;

    #[ORM\Column(type: Types::INTEGER)]
    private int $transactionCount = 0;

    public function __construct()
    {
        $this->lastUpdated = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getMonth(): \DateTimeImmutable
    {
        return $this->month;
    }

    public function setMonth(\DateTimeImmutable $month): static
    {
        // Ensure we always store the first day of the month
        $this->month = $month->modify('first day of this month')->setTime(0, 0, 0);

        return $this;
    }

    public function getEndOfMonthBalance(): float
    {
        return $this->endOfMonthBalance;
    }

    public function setEndOfMonthBalance(float $endOfMonthBalance): static
    {
        $this->endOfMonthBalance = $endOfMonthBalance;

        return $this;
    }

    public function getLastUpdated(): \DateTimeImmutable
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(\DateTimeImmutable $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    public function getTransactionCount(): int
    {
        return $this->transactionCount;
    }

    public function setTransactionCount(int $transactionCount): static
    {
        $this->transactionCount = $transactionCount;

        return $this;
    }

    public function updateLastUpdated(): static
    {
        $this->lastUpdated = new \DateTimeImmutable();

        return $this;
    }

    /**
     * Helper to create MonthlyBalance for a specific month.
     */
    public static function forMonth(Account $account, \DateTimeInterface $month): self
    {
        $monthlyBalance = new self();
        $monthlyBalance->setAccount($account);

        if ($month instanceof \DateTimeImmutable) {
            $monthlyBalance->setMonth($month);
        } else {
            $monthlyBalance->setMonth(\DateTimeImmutable::createFromInterface($month));
        }

        return $monthlyBalance;
    }
}
