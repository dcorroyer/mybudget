<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    /**
     * @var Collection<int, ChargeLine>
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: ChargeLine::class)]
    private Collection $chargeLines;

    public function __construct()
    {
        $this->chargeLines = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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
            $chargeLine->setCategory($this);
        }

        return $this;
    }

    public function removeChargeLine(ChargeLine $chargeLine): static
    {
        if ($this->chargeLines->removeElement($chargeLine)) {
            // set the owning side to null (unless already changed)
            if ($chargeLine->getCategory() === $this) {
                $chargeLine->setCategory(null);
            }
        }

        return $this;
    }
}
