<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\IncomeRepository;
use App\Serializable\SerializationGroups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IncomeRepository::class)]
#[ORM\Table(name: 'incomes')]
class Income
{
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
    #[ORM\Column]
    private float $amount;

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
}
