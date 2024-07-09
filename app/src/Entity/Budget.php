<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BudgetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Budget
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private ?Uuid $id;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name = '';

    #[Assert\NotBlank]
    #[Assert\Type(Types::FLOAT)]
    #[ORM\Column(type: Types::FLOAT)]
    private float $savingCapacity = 0;

    #[Context(
        normalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m',
        ],
        denormalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m',
        ],
    )]
    #[Assert\NotBlank]
    #[Assert\Date]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $date;

    /**
     * @var Collection<int, Income>
     */
    #[ORM\OneToMany(targetEntity: Income::class, mappedBy: 'budget', cascade: ['persist'], orphanRemoval: true)]
    private Collection $incomes;

    /**
     * @var Collection<int, Expense>
     */
    #[ORM\OneToMany(targetEntity: Expense::class, mappedBy: 'budget', cascade: ['persist'], orphanRemoval: true)]
    private Collection $expenses;

    #[ORM\ManyToOne(inversedBy: 'budgets')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->incomes = new ArrayCollection();
        $this->expenses = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): static
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

    #[ORM\PreFlush]
    public function updateName(): void
    {
        $this->name = 'Budget ' . $this->date->format('Y-m');
    }

    public function getSavingCapacity(): float
    {
        return $this->savingCapacity;
    }

    public function setSavingCapacity(float $savingCapacity): static
    {
        $this->savingCapacity = $savingCapacity;

        return $this;
    }

    public function calculateTotalIncomesAmount(): float
    {
        $totalIncomesAmount = 0.0;

        foreach ($this->incomes as $income) {
            $totalIncomesAmount += $income->getAmount();
        }

        return $totalIncomesAmount;
    }

    public function calculateTotalExpensesAmount(): float
    {
        $totalExpensesAmount = 0.0;

        foreach ($this->expenses as $expense) {
            $totalExpensesAmount += $expense->getAmount();
        }

        return $totalExpensesAmount;
    }

    #[ORM\PreFlush]
    public function updateSavingCapacity(): void
    {
        $this->savingCapacity = $this->calculateTotalIncomesAmount() - $this->calculateTotalExpensesAmount();
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

    /**
     * @return Collection<int, Income>
     */
    public function getIncomes(): Collection
    {
        return $this->incomes;
    }

    public function setIncomes(Collection $incomes): static
    {
        $this->incomes = $incomes;

        foreach ($incomes as $income) {
            $income->setBudget($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function setExpenses(Collection $expenses): static
    {
        $this->expenses = $expenses;

        foreach ($expenses as $expense) {
            $expense->setBudget($this);
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

    public function isOwnedByUser(?User $user): bool
    {
        return $this->getUser() === $user;
    }
}
