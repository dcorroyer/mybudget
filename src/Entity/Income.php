<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\IncomeRepository;
use App\Serializable\SerializationGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use My\RestBundle\Trait\TimestampableTrait;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: IncomeRepository::class)]
#[ORM\Table(name: 'incomes')]
class Income
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
    #[ORM\Column]
    private ?float $amount = 0;

    /**
     * @var Collection<IncomeLine>
     */
    #[Serializer\Groups([
        SerializationGroups::INCOME_GET,
        SerializationGroups::INCOME_LIST,
        SerializationGroups::INCOME_DELETE,
    ])]
    #[ORM\OneToMany(mappedBy: 'income', targetEntity: IncomeLine::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $incomeLines;

    public function __construct()
    {
        $this->incomeLines = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateAmount(): void
    {
        $this->amount = 0;

        foreach ($this->incomeLines as $incomeLine) {
            $this->amount += $incomeLine->getAmount();
        }
    }

    /**
     * @return Collection<int, IncomeLine>
     */
    public function getIncomeLines(): Collection
    {
        return $this->incomeLines;
    }

    public function addIncomeLine(IncomeLine $incomeLine): self
    {
        if (! $this->incomeLines->contains($incomeLine)) {
            $this->incomeLines[] = $incomeLine;
            $incomeLine->setIncome($this);
        }

        return $this;
    }

    public function removeIncomeLine(IncomeLine $incomeLine): self
    {
        if ($this->incomeLines->removeElement($incomeLine)) {
            // set the owning side to null (unless already changed)
            if ($incomeLine->getIncome() === $this) {
                $incomeLine->setIncome(null);
            }
        }

        return $this;
    }
}
