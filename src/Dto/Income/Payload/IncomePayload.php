<?php

declare(strict_types=1);

namespace App\Dto\Income\Payload;

use App\Serializable\SerializationGroups;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class IncomePayload implements PayloadInterface
{
    /**
     * @var array<int, IncomeLinePayload>
     */
    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    private array $incomeLines = [];

    /**
     * @return IncomeLinePayload[]
     */
    public function getIncomeLines(): array
    {
        return $this->incomeLines;
    }

    /**
     * @param IncomeLinePayload[] $incomeLines
     */
    public function setIncomeLines(array $incomeLines): self
    {
        $this->incomeLines = $incomeLines;

        return $this;
    }
}
