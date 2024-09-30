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
#[ORM\Table(name: '`expense`')]
class Expense
{
    #[Serializer\Groups([
        SerializationGroups::BUDGET_GET,
        SerializationGroups::BUDGET_CREATE,
        SerializationGroups::BUDGET_UPDATE,
    ])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Serializer\Groups([
        SerializationGroups::BUDGET_GET,
        SerializationGroups::BUDGET_CREATE,
        SerializationGroups::BUDGET_UPDATE,
    ])]
    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    #[ORM\Column(length: 255)]
    private string $name = '';

    #[Serializer\Groups([
        SerializationGroups::BUDGET_GET,
        SerializationGroups::BUDGET_CREATE,
        SerializationGroups::BUDGET_UPDATE,
    ])]
    #[Assert\NotBlank]
    #[Assert\Type(Types::FLOAT)]
    #[ORM\Column]
    private float $amount = 0;

    #[Serializer\Groups([
        SerializationGroups::BUDGET_GET,
        SerializationGroups::BUDGET_CREATE,
        SerializationGroups::BUDGET_UPDATE,
    ])]
    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    #[ORM\Column(length: 255)]
    private string $category = '';

    #[ORM\ManyToOne(targetEntity: Budget::class, cascade: ['persist'], fetch: 'LAZY', inversedBy: 'expenses')]
    #[ORM\JoinColumn(name: 'budget_id', referencedColumnName: 'id', nullable: false)]
    private ?Budget $budget = null;

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

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): static
    {
        $this->budget = $budget;

        return $this;
    }
}
