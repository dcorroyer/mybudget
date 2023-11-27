<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrackingRepository;
use App\Serializable\SerializationGroups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use My\RestBundle\Trait\TimestampableTrait;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrackingRepository::class)]
class Tracking
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Serializer\Groups([
        SerializationGroups::TRACKING_GET,
        SerializationGroups::TRACKING_LIST,
        SerializationGroups::TRACKING_DELETE,
    ])]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private string $name;

    #[Serializer\Groups([
        SerializationGroups::TRACKING_GET,
        SerializationGroups::TRACKING_LIST,
        SerializationGroups::TRACKING_DELETE,
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[ORM\Column]
    private ?float $savingCapacity = 0;

    #[Context(
        normalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m',
        ],
        denormalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m',
        ],
    )]
    #[Serializer\Groups([
        SerializationGroups::TRACKING_GET,
        SerializationGroups::TRACKING_LIST,
        SerializationGroups::TRACKING_DELETE,
    ])]
    #[Assert\NotBlank]
    #[Assert\Date]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $date;

    #[Serializer\Groups([
        SerializationGroups::TRACKING_GET,
        SerializationGroups::TRACKING_LIST,
        SerializationGroups::TRACKING_DELETE,
    ])]
    #[ORM\OneToOne(cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Income $income = null;

    #[Serializer\Groups([
        SerializationGroups::TRACKING_GET,
        SerializationGroups::TRACKING_LIST,
        SerializationGroups::TRACKING_DELETE,
    ])]
    #[ORM\OneToOne(cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Expense $expense = null;

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

    public function getSavingCapacity(): ?float
    {
        return $this->savingCapacity;
    }

    public function setSavingCapacity(?float $savingCapacity): self
    {
        $this->savingCapacity = $savingCapacity;

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

    public function getIncome(): ?Income
    {
        return $this->income;
    }

    public function setIncome(Income $income): self
    {
        $this->income = $income;

        return $this;
    }

    public function getExpense(): ?Expense
    {
        return $this->expense;
    }

    public function setExpense(Expense $expense): self
    {
        $this->expense = $expense;

        return $this;
    }
}
