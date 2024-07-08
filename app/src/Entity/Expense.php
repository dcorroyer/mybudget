<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
#[ORM\Table(name: 'expenses')]
class Expense
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $id;

    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name = '';

    #[Assert\NotBlank]
    #[Assert\Type(Types::FLOAT)]
    #[ORM\Column(type: Types::FLOAT)]
    private float $amount = 0;

    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $category = '';

    #[ORM\ManyToOne(targetEntity: Budget::class, cascade: ['persist'], inversedBy: 'expenses')]
    #[ORM\JoinColumn(name: 'budget_id', referencedColumnName: 'id')]
    private ?Budget $budget = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
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
