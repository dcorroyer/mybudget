<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ChargeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChargeRepository::class)]
#[ORM\Table(name: 'charges')]
class Charge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private float $amount;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $date;

    /**
     * @var Collection<int, ChargeLine>
     */
    #[ORM\OneToMany(mappedBy: 'charge', targetEntity: ChargeLine::class)]
    private Collection $chargeLines;

    public function __construct()
    {
        $this->chargeLines = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    /**
     * @return Collection<int, ChargeLine>
     */
    public function getChargeLines(): Collection
    {
        return $this->chargeLines;
    }

    public function addChargeLine(ChargeLine $chargeLine): static
    {
        if (! $this->chargeLines->contains($chargeLine)) {
            $this->chargeLines->add($chargeLine);
            $chargeLine->setCharge($this);
        }

        return $this;
    }

    public function removeChargeLine(ChargeLine $chargeLine): static
    {
        if ($this->chargeLines->removeElement($chargeLine)) {
            // set the owning side to null (unless already changed)
            if ($chargeLine->getCharge() === $this) {
                $chargeLine->setCharge(null);
            }
        }

        return $this;
    }
}
