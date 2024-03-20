---
title: Creating API Endpoint with Symfony Attributes
subtitle: Using Attributes for OpenApi Documentation
description: Learn to create an API endpoint in Symfony using attributes for OpenApi documentation with the NelmioApiDocBundle.
status: draft
tags:
    - Symfony
    - API
author: Your Name
---

# Creating API Endpoint with Symfony Attributes

---

# Description

This documentation guides you through the process of creating a Symfony API endpoint using new attributes introduced in Symfony, simplifying the generation of OpenApi documentation through the NelmioApiDocBundle.

# Context

Symfony has introduced new attributes to map data to Data Transfer Objects (DTOs). This documentation focuses on using #[MapQueryString] and #[MapRequestPayload] attributes to map query parameters and request payload to a DTO object. This process involves auto-validation through the Symfony validation component and the #[Assert] attribute.

For more information, refer to the Symfony documentation:

- [Mapping the Whole Query String](https://symfony.com/doc/current/controller.html#mapping-the-whole-query-string)
- [Mapping Request Payload](https://symfony.com/doc/current/controller.html#mapping-request-payload)

Additionally, thanks to the NelmioApiDocBundle, OpenApi documentation is generated seamlessly through these attributes, making it more readable, simpler to write, and less error-prone.

# Prerequisites

Before following this documentation, make sure you are familiar with Symfony 6.3 and later. 

You should also have the NelmioApiDocBundle installed and configured in your Symfony application.

--- 

# Documentation

## Attributes Used

Symfony introduces the following attributes for mapping data to DTOs:

- `#[MapQueryString]`: Maps query parameters to a DTO object with auto-validation.
- `#[MapRequestPayload]`: Maps request payload to a DTO object with auto-validation, suitable for POST, PUT, PATCH, etc.

## OpenApiResponse Attribute

The `#[OpenApiResponse]` attribute is created to document API responses. It is repeatable, allowing documentation of multiple responses for a single endpoint. 

Parameters include:

- `class`: The class to be used as the response.
- `groups`: The groups used to serialize the response.
- `type`: The type of the response, either "ResponseType::ITEM" or "ResponseType::COLLECTION".
- `statusCode`: The status code of the response.

Example for documenting a collection of User with a specific group:
```php
#[OpenApiResponse(User::class, type: ResponseType::COLLECTION, groups: ['group1'])]
```

Example for documenting a single User:
```php
#[OpenApiResponse(User::class)]
```

## OpenApiMeta Attribute

The `#[OpenApiMeta]` attribute is created to document meta information of the response, such as pagination details, total, current page, etc. 

Parameters include:

- `class`: The class to be used as meta information.
- `groups`: The groups used to serialize meta information.

## BadRequestResponse Attribute

The `#[BadRequestResponse]` attribute documents the case when a bad request is thrown by `#[MapQueryString]` or `#[MapRequestPayload]`. 

It references the `App\Api\Normalizer\ConstraintNormalizer` class, which handles validation errors or normalizer errors.

## API Controller Example

```php
#[AsController]
#[ApiRoute('/api/users', method: HttpMethod::GET)]
class GetCollectionUserController
{
    #[OpenApiResponse(User::class, type: ResponseType::COLLECTION)]
    #[OpenApiMeta(UserMeta::class)]
    #[BadRequestResponse]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: 400)] ?UserFilterQuery $filterQuery
    ): ApiResponse {
        return new ApiResponse(
            UserCollection::fromIterable([
                (new User())->setName('John Doe')->setEmail('john@doe.fr'),
                (new User())->setName('Alice Cooper')->setEmail('alice@cooper.fr'),
            ]),
            new UserMeta(
                total: 2,
                page: 1,
                limit: 10
            )
        );
    }
}
```

## UserFilterQuery DTO Object

```php
class UserFilterQuery
{
    #[Property(description: 'The query to search for')]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $query = null;
}
```

## UserMeta DTO Object

```php
class UserMeta
{
    public function __construct(
        public int $total,
        public int $page,
        public int $limit,
    ) {
    }
}
```

## API Output Examples

### Output for `/api/users`

```json
{
    "data": [
        {
            "name": "John Doe",
            "email": "john@doe.fr"
        },
        {
            "name": "Alice Cooper",
            "email": "alice@cooper.fr"
        }
    ],
    "meta": {
        "total": 2,
        "page": 1,
        "limit": 10
    }
}
```

### Output for `/api/users?query=alice`

```json
{
    "data": [
        {
            "name": "Alice Cooper",
            "email": "alice@cooper.fr"
        }
    ],
    "meta": {
        "total": 1,
        "page": 1,
        "limit": 10
    }
}
```

# Conclusion

By utilizing Symfony attributes, you can simplify the creation and documentation of API endpoints, making your code more readable and reducing the chance of errors.

# References

- [Symfony Documentation - Mapping the Whole Query String](https://symfony.com/doc/current/controller.html#mapping-the-whole-query-string)
- [Symfony Documentation - Mapping Request Payload](https://symfony.com/doc/current/controller.html#mapping-request-payload)
- [NelmioApiDocBundle Documentation](https://github.com/nelmio/NelmioApiDocBundle)
- [Symfony Validation Component](https://symfony.com/doc/current/validation.html)
