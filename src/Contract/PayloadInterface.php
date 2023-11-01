<?php

declare(strict_types=1);

namespace App\Contract;

/**
 * Interface PayloadInterface.
 *
 * This interface should be implemented by classes that represent payloads for endpoints that require a payload (e.g. POST, PUT, PATCH).
 * The payload is the data that is sent to the endpoint to be processed.
 *
 * Classes that implement this interface should have properties and corresponding getter and setter methods for each property in the payload.
 * The names and types of these properties should match the structure of the expected payload for the endpoint.
 *
 * It is possible to use assertions for ensuring the valid construction of the payload.
 */
interface PayloadInterface
{
}
