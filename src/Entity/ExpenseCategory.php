<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExpenseCategoryRepository;
use App\Serializable\SerializationGroups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExpenseCategoryRepository::class)]
#[ORM\Table(name: 'expense_line_categories')]
#[UniqueEntity(fields: 'name', message: 'There is already an expense line category with this name')]
class ExpenseCategory
{
    #[Serializer\Groups([
        SerializationGroups::EXPENSE_GET,
        SerializationGroups::EXPENSE_LIST,
        SerializationGroups::EXPENSE_DELETE,
        SerializationGroups::EXPENSE_CATEGORY_GET,
        SerializationGroups::EXPENSE_CATEGORY_LIST,
        SerializationGroups::TRACKING_GET,
    ])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Serializer\Groups([
        SerializationGroups::EXPENSE_GET,
        SerializationGroups::EXPENSE_LIST,
        SerializationGroups::EXPENSE_DELETE,
        SerializationGroups::EXPENSE_CATEGORY_GET,
        SerializationGroups::EXPENSE_CATEGORY_LIST,
        SerializationGroups::TRACKING_GET,
    ])]
    #[Assert\NotBlank]
    #[Assert\Unique]
    #[ORM\Column(length: 255)]
    private string $name;

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
}
