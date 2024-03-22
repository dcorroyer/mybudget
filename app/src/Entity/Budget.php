<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BudgetRepository;
use App\Serializable\SerializationGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use My\RestBundle\Trait\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
#[ORM\Table(name: 'budgets')]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: 'name', message: 'There is already a budget with this name')]
class Budget
{
    use TimestampableTrait;

    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_LIST, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_DELETE])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_LIST, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_DELETE])]
    #[Assert\NotBlank]
    #[Assert\Unique]
    #[ORM\Column(length: 255)]
    private string $name;

    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_LIST, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_DELETE])]
    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[ORM\Column]
    private ?float $savingCapacity = 0;

    #[Context(
        normalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m',
        ],
        denormalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m',
        ],
    )]
    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_LIST, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_DELETE])]
    #[Assert\NotBlank]
    #[Assert\Date]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $date;

    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_LIST, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_DELETE])]
    #[ORM\OneToMany(mappedBy: 'budget', targetEntity: Income::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $incomes;

    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_LIST, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_DELETE])]
    #[ORM\OneToMany(mappedBy: 'budget', targetEntity: Expense::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $expenses;

    #[ORM\ManyToOne(inversedBy: 'budgets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->incomes = new ArrayCollection();
        $this->expenses = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateName(): void
    {
        $this->name = 'Budget ' . $this->date->format('Y-m');
    }

    public function getSavingCapacity(): ?float
    {
        return $this->savingCapacity;
    }

    public function setSavingCapacity(?float $savingCapacity): self
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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateSavingCapacity(): void
    {
        $this->savingCapacity = $this->calculateTotalIncomesAmount() - $this->calculateTotalExpensesAmount();
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIncomes(): Collection
    {
        return $this->incomes;
    }

    public function setIncomes(Collection $incomes): Budget
    {
        $this->incomes = $incomes;

        return $this;
    }

    public function addIncome(Income $income): void
    {
        if (!$this->incomes->contains($income)) {
            $income->setBudget($this);
            $this->incomes[] = $income;
        }
    }

    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function setExpenses(Collection $expenses): Budget
    {
        $this->expenses = $expenses;

        return $this;
    }

    public function addExpense(Expense $expense): void
    {
        if (!$this->expenses->contains($expense)) {
            $expense->setBudget($this);
            $this->expenses[] = $expense;
        }
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
