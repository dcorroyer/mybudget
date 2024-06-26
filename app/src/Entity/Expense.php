<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExpenseRepository;
use App\Serializable\SerializationGroups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
#[ORM\Table(name: 'expenses')]
class Expense
{
    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_UPDATE])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_UPDATE])]
    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    #[ORM\Column(length: 255)]
    private string $name;

    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_UPDATE])]
    #[Assert\NotBlank]
    #[Assert\Type(Types::FLOAT)]
    #[ORM\Column]
    private float $amount;

    #[Serializer\Groups([SerializationGroups::BUDGET_GET, SerializationGroups::BUDGET_CREATE, SerializationGroups::BUDGET_UPDATE])]
    #[ORM\ManyToOne(targetEntity: ExpenseCategory::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ExpenseCategory $expenseCategory;

    #[ORM\ManyToOne(targetEntity: Budget::class, cascade: ['persist'], fetch: 'LAZY', inversedBy: 'expenses')]
    #[ORM\JoinColumn(name: 'budget_id', referencedColumnName: 'id', nullable: false)]
    private ?Budget $budget = null;

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

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getExpenseCategory(): ExpenseCategory
    {
        return $this->expenseCategory;
    }

    public function setExpenseCategory(ExpenseCategory $expenseCategory): self
    {
        $this->expenseCategory = $expenseCategory;

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): self
    {
        $this->budget = $budget;

        return $this;
    }
}
