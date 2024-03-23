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
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
#[ORM\Table(name: 'budgets')]
#[ORM\HasLifecycleCallbacks]
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

    /**
     * @var Collection<Income>
     */
    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_LIST, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_DELETE])]
    #[ORM\OneToMany(mappedBy: 'budget', targetEntity: Income::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $incomes;

    /**
     * @var Collection<Expense>
     */
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

    public function setId(int $id): static
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

    public function getSavingCapacity(): ?float
    {
        return $this->savingCapacity;
    }

    public function setSavingCapacity(?float $savingCapacity): static
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

    public function addIncome(Income $income): static
    {
        if (!$this->incomes->contains($income)) {
            $this->incomes[] = $income;
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

    public function addExpense(Expense $expense): static
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses[] = $expense;
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
