<?php

declare(strict_types=1);

namespace App\Dto\Income\Response;

use App\Serializable\SerializationGroups;
use App\Trait\Response\AmountResponseTrait;
use App\Trait\Response\IdResponseTrait;
use My\RestBundle\Contract\ResponseInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class IncomeResponse implements ResponseInterface
{
    use IdResponseTrait;
    use AmountResponseTrait;

    /**
     * @var array<IncomeLineResponse>
     */
    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    private array $incomeLines;

    /**
     * @return IncomeLineResponse[]
     */
    public function getIncomeLines(): array
    {
        return $this->incomeLines;
    }

    /**
     * @param IncomeLineResponse[] $incomeLines
     */
    public function setIncomeLines(array $incomeLines): self
    {
        $this->incomeLines = $incomeLines;

        return $this;
    }
}
