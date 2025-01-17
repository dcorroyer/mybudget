<?php

declare(strict_types=1);

namespace App\Budget\Entity;

use App\Budget\Repository\IncomeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IncomeRepository::class)]
#[ORM\Table(name: '`income`')]
class Income
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name = '';

    #[Assert\NotBlank]
    #[Assert\Type(Types::FLOAT)]
    #[ORM\Column(type: Types::FLOAT)]
    private float $amount = 0;

    #[ORM\ManyToOne(targetEntity: Budget::class, inversedBy: 'incomes')]
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
