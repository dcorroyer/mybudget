<?php

declare(strict_types=1);

namespace App\Dto\Tracking\Response;

use App\Entity\Expense;
use App\Entity\Income;
use App\Serializable\SerializationGroups;
use App\Trait\Response\DateResponseTrait;
use App\Trait\Response\IdResponseTrait;
use App\Trait\Response\NameResponseTrait;
use My\RestBundle\Contract\ResponseInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class TrackingResponse implements ResponseInterface
{
    use IdResponseTrait;
    use NameResponseTrait;
    use DateResponseTrait;

    #[Serializer\Groups([SerializationGroups::TRACKING_CREATE, SerializationGroups::TRACKING_UPDATE])]
    private float $savingCapacity;

    #[Serializer\Groups([SerializationGroups::TRACKING_CREATE, SerializationGroups::TRACKING_UPDATE])]
    private Income $income;

    #[Serializer\Groups([SerializationGroups::TRACKING_CREATE, SerializationGroups::TRACKING_UPDATE])]
    private Expense $expense;

    public function getSavingCapacity(): float
    {
        return $this->savingCapacity;
    }

    public function setSavingCapacity(float $savingCapacity): self
    {
        $this->savingCapacity = $savingCapacity;

        return $this;
    }

    public function getIncome(): Income
    {
        return $this->income;
    }

    public function setIncome(Income $income): self
    {
        $this->income = $income;

        return $this;
    }

    public function getExpense(): Expense
    {
        return $this->expense;
    }

    public function setExpense(Expense $expense): self
    {
        $this->expense = $expense;

        return $this;
    }
}
