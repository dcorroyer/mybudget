<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use App\Serializable\SerializationGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Serializer\Groups([SerializationGroups::USER_GET, SerializationGroups::USER_CREATE])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Serializer\Groups([SerializationGroups::USER_GET, SerializationGroups::USER_CREATE])]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 180)]
    private string $email = '';

    #[Serializer\Groups([SerializationGroups::USER_GET, SerializationGroups::USER_CREATE])]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 180)]
    private string $firstName = '';

    #[Serializer\Groups([SerializationGroups::USER_GET, SerializationGroups::USER_CREATE])]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 180)]
    private string $lastName = '';

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 180)]
    private string $password = '';

    /**
     * @var Collection<int, Budget>
     */
    #[Serializer\Groups([SerializationGroups::USER_GET])]
    #[ORM\OneToMany(targetEntity: Budget::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $budgets;

    public function __construct()
    {
        $this->budgets = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return Collection<int, Budget>
     */
    public function getBudgets(): Collection
    {
        return $this->budgets;
    }

    public function addBudget(Budget $budget): self
    {
        if (! $this->budgets->contains($budget)) {
            $this->budgets->add($budget);
            $budget->setUser($this);
        }

        return $this;
    }

    public function removeBudget(Budget $budget): self
    {
        // set the owning side to null (unless already changed)
        if ($this->budgets->removeElement($budget) && $budget->getUser() === $this) {
            $budget->setUser(null);
        }

        return $this;
    }
}
