<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\IncomeTypes;
use App\Repository\IncomeLineRepository;
use App\Serializable\SerializationGroups;
use Doctrine\ORM\Mapping as ORM;
use My\RestBundle\Trait\TimestampableTrait;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IncomeLineRepository::class)]
#[ORM\Table(name: 'income_lines')]
class IncomeLine
{
    use TimestampableTrait;

    #[Serializer\Groups([
        SerializationGroups::INCOME_GET,
        SerializationGroups::INCOME_LIST,
        SerializationGroups::INCOME_DELETE,
    ])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Serializer\Groups([
        SerializationGroups::INCOME_GET,
        SerializationGroups::INCOME_LIST,
        SerializationGroups::INCOME_DELETE,
    ])]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private string $name;

    #[Serializer\Groups([
        SerializationGroups::INCOME_GET,
        SerializationGroups::INCOME_LIST,
        SerializationGroups::INCOME_DELETE,
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[ORM\Column]
    private float $amount;

    #[Serializer\Groups([
        SerializationGroups::INCOME_GET,
        SerializationGroups::INCOME_LIST,
        SerializationGroups::INCOME_DELETE,
    ])]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private IncomeTypes $type;

    #[ORM\ManyToOne(targetEntity: Income::class, inversedBy: 'incomeLines')]
    #[ORM\JoinColumn(name: 'income_id', referencedColumnName: 'id', nullable: false)]
    private ?Income $income = null;

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

    public function getType(): IncomeTypes
    {
        return $this->type;
    }

    public function setType(IncomeTypes $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIncome(): ?Income
    {
        return $this->income;
    }

    public function setIncome(?Income $income): static
    {
        $this->income = $income;

        return $this;
    }
}
