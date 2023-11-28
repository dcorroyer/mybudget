<?php

declare(strict_types=1);

namespace App\Dto\Tracking\Payload;

use App\Serializable\SerializationGroups;
use App\Trait\Payload\DatePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class TrackingPayload implements PayloadInterface
{
    use DatePayloadTrait;

    #[Serializer\Groups([SerializationGroups::TRACKING_CREATE])]
    private int $incomeId;

    #[Serializer\Groups([SerializationGroups::TRACKING_CREATE])]
    private int $expenseId;

    public function getIncomeId(): int
    {
        return $this->incomeId;
    }

    public function setIncomeId(int $incomeId): self
    {
        $this->incomeId = $incomeId;

        return $this;
    }

    public function getExpenseId(): int
    {
        return $this->expenseId;
    }

    public function setExpenseId(int $expenseId): self
    {
        $this->expenseId = $expenseId;

        return $this;
    }
}
