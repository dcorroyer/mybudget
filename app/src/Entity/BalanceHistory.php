<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BalanceHistoryRepository;
use App\Serializable\SerializationGroups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: BalanceHistoryRepository::class)]
#[ORM\Table(name: '`balance_history`')]
class BalanceHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Serializer\Groups([SerializationGroups::BALANCE_HISTORY_GET])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Serializer\Groups([SerializationGroups::BALANCE_HISTORY_GET])]
    private \DateTimeInterface $date;

    #[ORM\Column(type: Types::FLOAT)]
    #[Serializer\Groups([SerializationGroups::BALANCE_HISTORY_GET])]
    private float $balance = 0.0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Account $account;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Transaction $transaction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
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

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): static
    {
        $this->balance = $balance;

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

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(Transaction $transaction): static
    {
        $this->transaction = $transaction;

        return $this;
    }
}
