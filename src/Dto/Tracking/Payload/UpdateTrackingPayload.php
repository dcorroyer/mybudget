<?php

declare(strict_types=1);

namespace App\Dto\Tracking\Payload;

use App\Trait\Payload\DatePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;

final class UpdateTrackingPayload implements PayloadInterface
{
    use DatePayloadTrait;
}
