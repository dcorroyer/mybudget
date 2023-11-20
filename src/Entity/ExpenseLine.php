<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExpenseLineRepository;
use App\Serializable\SerializationGroups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: ExpenseLineRepository::class)]
#[ORM\Table(name: 'expense_lines')]
class ExpenseLine
{
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
    #[ORM\Column(length: 255)]
    private string $name;

    #[Serializer\Groups([
        SerializationGroups::EXPENSE_GET,
        SerializationGroups::EXPENSE_LIST,
        SerializationGroups::EXPENSE_DELETE,
    ])]
    #[ORM\Column]
    private float $amount;

    #[Serializer\Groups([
        SerializationGroups::EXPENSE_GET,
        SerializationGroups::EXPENSE_LIST,
        SerializationGroups::EXPENSE_DELETE,
    ])]
    #[ORM\ManyToOne(targetEntity: Category::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false)]
    private Category $category;

    #[ORM\ManyToOne(targetEntity: Expense::class, inversedBy: 'expenseLines')]
    #[ORM\JoinColumn(name: 'expense_id', referencedColumnName: 'id', nullable: false)]
    private ?Expense $expense = null;

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

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getExpense(): ?Expense
    {
        return $this->expense;
    }

    public function setExpense(?Expense $expense): self
    {
        $this->expense = $expense;

        return $this;
    }
}
