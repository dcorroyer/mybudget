<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    /**
     * @var Collection<int, ExpenseLine>
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: ExpenseLine::class)]
    private Collection $expenseLines;

    public function __construct()
    {
        $this->expenseLines = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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
            $expenseLine->setCategory($this);
        }

        return $this;
    }

    public function removeExpenseLine(ExpenseLine $expenseLine): static
    {
        if ($this->expenseLines->removeElement($expenseLine)) {
            // set the owning side to null (unless already changed)
            if ($expenseLine->getCategory() === $this) {
                $expenseLine->setCategory(null);
            }
        }

        return $this;
    }
}
