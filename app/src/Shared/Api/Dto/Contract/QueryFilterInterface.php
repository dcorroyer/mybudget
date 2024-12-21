<?php

declare(strict_types=1);

namespace App\Shared\Api\Dto\Contract;

/**
 * Interface FilterQueryInterface.
 *
 * This interface should be implemented when your endpoint utilizes filter queries.
 *
 * For instance, if your API endpoint is something like `/api/resource?foo=bar`,
 * you should create a class implementing this interface.
 * The class should then have `foo` as a property, complete with its own getter and setter methods.
 * The kind of assertions to be made is your own decision.
 *
 * Implementing this interface for your filter query parameters will ensure
 * cohesive interaction with the endpoint and other components which rely on it.
 */
interface QueryFilterInterface
{
}
