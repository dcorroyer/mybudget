<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BalanceHistoryRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: BalanceHistoryRepository::class)]
#[ORM\Table(name: '`balance_history`')]
class BalanceHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Context([
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
    ])]
    private \DateTimeInterface $date;

    #[ORM\Column(type: Types::FLOAT)]
    private float $balanceBeforeTransaction = 0.0;

    #[ORM\Column(type: Types::FLOAT)]
    private float $balanceAfterTransaction = 0.0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Transaction $transaction = null;

    public function __construct()
    {
        $this->date = Carbon::now();
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

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getBalanceBeforeTransaction(): float
    {
        return $this->balanceBeforeTransaction;
    }

    public function setBalanceBeforeTransaction(float $balanceBeforeTransaction): static
    {
        $this->balanceBeforeTransaction = $balanceBeforeTransaction;

        return $this;
    }

    public function getBalanceAfterTransaction(): float
    {
        return $this->balanceAfterTransaction;
    }

    public function setBalanceAfterTransaction(float $balanceAfterTransaction): static
    {
        $this->balanceAfterTransaction = $balanceAfterTransaction;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): static
    {
        $this->transaction = $transaction;

        return $this;
    }
}
