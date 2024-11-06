<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TransactionTypesEnum;
use App\Repository\TransactionRepository;
use App\Serializable\SerializationGroups;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\Table(name: '`transaction`')]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Serializer\Groups([
        SerializationGroups::TRANSACTION_GET,
        SerializationGroups::TRANSACTION_LIST,
        SerializationGroups::TRANSACTION_CREATE,
        SerializationGroups::TRANSACTION_UPDATE,
    ])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    #[ORM\Column(length: 255)]
    #[Serializer\Groups([
        SerializationGroups::TRANSACTION_GET,
        SerializationGroups::TRANSACTION_LIST,
        SerializationGroups::TRANSACTION_CREATE,
        SerializationGroups::TRANSACTION_UPDATE,
    ])]
    private string $description = '';

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    #[ORM\Column]
    #[Serializer\Groups([
        SerializationGroups::TRANSACTION_GET,
        SerializationGroups::TRANSACTION_LIST,
        SerializationGroups::TRANSACTION_CREATE,
        SerializationGroups::TRANSACTION_UPDATE,
    ])]
    private float $amount = 0;

    #[ORM\Column(type: Types::STRING, enumType: TransactionTypesEnum::class)]
    #[Serializer\Groups([
        SerializationGroups::TRANSACTION_GET,
        SerializationGroups::TRANSACTION_LIST,
        SerializationGroups::TRANSACTION_CREATE,
        SerializationGroups::TRANSACTION_UPDATE,
    ])]
    private TransactionTypesEnum $type = TransactionTypesEnum::CREDIT;

    #[Assert\NotBlank]
    #[Assert\DateTime]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Serializer\Groups([
        SerializationGroups::TRANSACTION_GET,
        SerializationGroups::TRANSACTION_LIST,
        SerializationGroups::TRANSACTION_CREATE,
        SerializationGroups::TRANSACTION_UPDATE,
    ])]
    #[Context([
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
    ])]
    private \DateTimeInterface $date;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Serializer\Groups([SerializationGroups::TRANSACTION_GET, SerializationGroups::TRANSACTION_LIST])]
    private ?Account $account = null;

    public function __construct()
    {
        $this->date = Carbon::now();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getType(): TransactionTypesEnum
    {
        return $this->type;
    }

    public function setType(TransactionTypesEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }
}
