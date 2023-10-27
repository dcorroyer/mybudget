<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
#[ORM\Table(name: 'expenses')]
class Expense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private float $amount;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $date;

    /**
     * @var Collection<int, ExpenseLine>
     */
    #[ORM\OneToMany(mappedBy: 'expense', targetEntity: ExpenseLine::class)]
    private Collection $expenseLines;

    public function __construct()
    {
        $this->expenseLines = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
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

    public function addExpenseLine(ExpenseLine $expenseLine): static
    {
        if (! $this->expenseLines->contains($expenseLine)) {
            $this->expenseLines->add($expenseLine);
            $expenseLine->setExpense($this);
        }

        return $this;
    }

    public function removeExpenseLine(ExpenseLine $expenseLine): static
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
