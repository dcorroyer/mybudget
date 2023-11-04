<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\IncomeTypes;
use App\Repository\IncomeRepository;
use App\Serializable\SerializationGroups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IncomeRepository::class)]
#[ORM\Table(name: 'incomes')]
class Income
{
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
    #[ORM\Column]
    private float $amount = 0;

    #[Context(
        normalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        ],
        denormalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        ],
    )]
    #[Serializer\Groups([
        SerializationGroups::INCOME_GET,
        SerializationGroups::INCOME_LIST,
        SerializationGroups::INCOME_DELETE,
    ])]
    #[Assert\NotBlank]
    #[Assert\Date]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $date;

    #[Serializer\Groups([
        SerializationGroups::INCOME_GET,
        SerializationGroups::INCOME_LIST,
        SerializationGroups::INCOME_DELETE,
    ])]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private IncomeTypes $type;

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

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
}
