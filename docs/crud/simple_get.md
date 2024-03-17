# How to make a simple GET request

To make a simple GET request you just have to create a controller and use the `#[AsController]` and `#[ApiRoute]` attributes, then you have to create a method
that will return the response, and that's it.

```php
<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Trait\EntityManagerTrait;use App\User\Entity\UserEntity;use App\User\Exception\UserNotFoundException;use App\User\Serialization\UserGroups;use Module\Api\Attribut\ApiRoute;use Module\Api\Attribut\OpenApiResponse;use Module\Api\Dto\ApiResponse;use Module\Api\Enum\HttpMethodEnum;use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * @see \App\Tests\User\Controller\GetUserByIdControllerTest
 */
#[AsController]
#[ApiRoute('/api/users/{id}', httpMethodEnum: HttpMethodEnum::GET)]
class GetUserByIdController
{
    use EntityManagerTrait;

    /**
     * @return ApiResponse<UserEntity, null>
     */
    #[OpenApiResponse(UserEntity::class)]
    public function __invoke(int $id): ApiResponse
    {
        $user = $this->em->find(UserEntity::class, $id);

        if ($user === null) {
            throw new UserNotFoundException([
                'userId' => $id,
            ]);
        }

        return new ApiResponse(data: $user, groups: [UserGroups::READ]);
    }
}
```

In this example, we have a controller that will return a user by its id, we use the `#[AsController]` and `#[ApiRoute]` attributes to define the route and the
method, and we use the `#[OpenApiResponse]` attribute to define the response type for documentation purposes.

In this case, the request will be like this:

```http request
GET /api/users/1
```