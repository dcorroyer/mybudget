<?php

declare(strict_types=1);

namespace App\Dto\Tracking\Payload;

use App\Trait\Payload\DatePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;

class UpdateTrackingPayload implements PayloadInterface
{
    use DatePayloadTrait;
}
