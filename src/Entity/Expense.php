<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExpenseRepository;
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

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
#[ORM\Table(name: 'expenses')]
#[ORM\HasLifecycleCallbacks]
class Expense
{
    use TimestampableTrait;

    #[Serializer\Groups([
        SerializationGroups::EXPENSE_GET,
        SerializationGroups::EXPENSE_LIST,
        SerializationGroups::EXPENSE_DELETE,
    ])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Serializer\Groups([
        SerializationGroups::EXPENSE_GET,
        SerializationGroups::EXPENSE_LIST,
        SerializationGroups::EXPENSE_DELETE,
    ])]
    #[ORM\Column]
    private ?float $amount = 0;

    #[Serializer\Groups([
        SerializationGroups::EXPENSE_GET,
        SerializationGroups::EXPENSE_LIST,
        SerializationGroups::EXPENSE_DELETE,
    ])]
    #[Context(
        normalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        ],
        denormalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        ],
    )]
    #[Assert\NotBlank]
    #[Assert\Date]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $date;

    /**
     * @var Collection<ExpenseLine>
     */
    #[Serializer\Groups([
        SerializationGroups::EXPENSE_GET,
        SerializationGroups::EXPENSE_LIST,
        SerializationGroups::EXPENSE_DELETE,
    ])]
    #[ORM\OneToMany(mappedBy: 'expense', targetEntity: ExpenseLine::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $expenseLines;

    public function __construct()
    {
        $this->expenseLines = new ArrayCollection();
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateAmount(): void
    {
        $this->amount = 0;

        foreach ($this->expenseLines as $expenseLine) {
            $this->amount += $expenseLine->getAmount();
        }
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

    /**
     * @return Collection<int, ExpenseLine>
     */
    public function getExpenseLines(): Collection
    {
        return $this->expenseLines;
    }

    public function addExpenseLine(ExpenseLine $expenseLine): self
    {
        if (! $this->expenseLines->contains($expenseLine)) {
            $this->expenseLines[] = $expenseLine;
            $expenseLine->setExpense($this);
        }

        return $this;
    }

    public function removeExpenseLine(ExpenseLine $expenseLine): self
    {
        if ($this->expenseLines->removeElement($expenseLine)) {
            // set the owning side to null (unless already changed)
            if ($expenseLine->getExpense() === $this) {
                $expenseLine->setExpense(null);
            }
        }

        return $this;
    }
}
