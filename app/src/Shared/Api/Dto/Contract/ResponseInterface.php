<?php

declare(strict_types=1);

namespace App\Shared\Api\Dto\Contract;

/**
 * Interface ResponseInterface.
 *
 * This interface should be implemented by classes that represent responses for endpoints that require a payload (e.g. POST, PUT, PATCH).
 * The response is the data that is sent after the process of the endpoint.
 *
 * Classes that implement this interface should have properties and corresponding getter and setter methods for each property in the response.
 * The names and types of these properties should match the structure of the expected response for the endpoint.
 *
 * It is possible to use assertions for ensuring the valid construction of the response.
 */
interface ResponseInterface
{
}
