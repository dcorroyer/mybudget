<?php

declare(strict_types=1);

namespace App\Dto\Income\Response;

use App\Enum\IncomeTypes;
use App\Serializable\SerializationGroups;
use App\Trait\Response\AmountResponseTrait;
use App\Trait\Response\IdResponseTrait;
use App\Trait\Response\NameResponseTrait;
use My\RestBundle\Contract\ResponseInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class IncomeLineResponse implements ResponseInterface
{
    use IdResponseTrait;
    use NameResponseTrait;
    use AmountResponseTrait;

    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    private IncomeTypes $type;

    public function getType(): IncomeTypes
    {
        return $this->type;
    }

    public function setType(IncomeTypes $type): self
    {
        $this->type = $type;

        return $this;
    }
}
