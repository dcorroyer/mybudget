<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BudgetRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
#[ORM\Table(name: '`budget`')]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['date'], message: 'This budget already exists.')]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    #[ORM\Column(length: 255)]
    private string $name = '';

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    #[ORM\Column]
    private float $incomesAmount = 0;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    #[ORM\Column]
    private float $expensesAmount = 0;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    #[ORM\Column]
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
    #[ORM\Column(type: Types::DATE_MUTABLE, unique: true)]
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
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->date = Carbon::now();
        $this->incomes = new ArrayCollection();
        $this->expenses = new ArrayCollection();
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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
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

    public function getIncomesAmount(): float
    {
        return $this->incomesAmount;
    }

    public function setIncomesAmount(float $incomesAmount): static
    {
        $this->incomesAmount = $incomesAmount;

        return $this;
    }

    #[ORM\PreFlush]
    public function updateIncomesAmount(): void
    {
        $this->incomesAmount = $this->calculateTotalIncomesAmount();
    }

    public function getExpensesAmount(): float
    {
        return $this->expensesAmount;
    }

    public function setExpensesAmount(float $expensesAmount): static
    {
        $this->expensesAmount = $expensesAmount;

        return $this;
    }

    #[ORM\PreFlush]
    public function updateExpensesAmount(): void
    {
        $this->expensesAmount = $this->calculateTotalExpensesAmount();
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

    public function clearIncomes(): void
    {
        $this->incomes->clear();
    }

    public function addIncome(Income $income): self
    {
        if (! $this->incomes->contains($income)) {
            $this->incomes->add($income);
            $income->setBudget($this);
        }

        return $this;
    }

    public function removeIncome(Income $income): static
    {
        if ($this->incomes->removeElement($income) && $income->getBudget() === $this) {
            $income->setBudget(null);
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

    public function clearExpenses(): void
    {
        $this->expenses->clear();
    }

    public function addExpense(Expense $expense): self
    {
        if (! $this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setBudget($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): static
    {
        if ($this->expenses->removeElement($expense) && $expense->getBudget() === $this) {
            $expense->setBudget(null);
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
