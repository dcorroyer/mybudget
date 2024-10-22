<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\AccountTypesEnum;
use App\Enum\TransactionTypesEnum;
use App\Repository\AccountRepository;
use App\Serializable\SerializationGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: '`account`')]
class Account
{
    #[Serializer\Groups([
        SerializationGroups::ACCOUNT_GET,
        SerializationGroups::ACCOUNT_LIST,
        SerializationGroups::ACCOUNT_CREATE,
        SerializationGroups::ACCOUNT_UPDATE,
    ])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Serializer\Groups([
        SerializationGroups::ACCOUNT_GET,
        SerializationGroups::ACCOUNT_LIST,
        SerializationGroups::ACCOUNT_CREATE,
        SerializationGroups::ACCOUNT_UPDATE,
    ])]
    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    #[ORM\Column(length: 255)]
    private string $name = '';

    #[Serializer\Groups([
        SerializationGroups::ACCOUNT_GET,
        SerializationGroups::ACCOUNT_LIST,
        SerializationGroups::ACCOUNT_CREATE,
        SerializationGroups::ACCOUNT_UPDATE,
    ])]
    #[ORM\Column(type: Types::STRING, enumType: AccountTypesEnum::class)]
    private AccountTypesEnum $type = AccountTypesEnum::SAVINGS;

    /**
     * @var Collection<int, Transaction>
     */
    #[Serializer\Groups([SerializationGroups::ACCOUNT_GET])]
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'account', orphanRemoval: true)]
    private Collection $transactions;

    #[ORM\ManyToOne(inversedBy: 'budgets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    private function calculateBalance(): float
    {
        return $this->transactions->reduce(
            static function (float $balance, Transaction $transaction): float {
                $amount = $transaction->getAmount();

                return $balance + ($transaction->getType() === TransactionTypesEnum::CREDIT ? $amount : -$amount);
            },
            0.0
        );
    }

    #[Serializer\Groups([SerializationGroups::ACCOUNT_GET, SerializationGroups::ACCOUNT_LIST])]
    public function getBalance(): float
    {
        return $this->calculateBalance();
    }

    public function getType(): AccountTypesEnum
    {
        return $this->type;
    }

    public function setType(AccountTypesEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (! $this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setAccount($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction) && $transaction->getAccount() === $this) {
            $transaction->setAccount(null);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
