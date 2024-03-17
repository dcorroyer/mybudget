# How to make a paginated request with filter

# For making a paginated request with filter, you have to use the new `#[MapQueryString]` attribute to map the query string parameters to the filter object.

> [!NOTE]
>
> FilterQuery Object should implements `FilterQueryInterface` interface.

Example:

FilterQuery Object :

```php
<?php

declare(strict_types=1);

namespace App\User\Api;

use Doctrine\ORM\QueryBuilder;
use Module\Api\Adapter\ORMFilterQueryInterface;
use OpenApi\Attributes\Property;
use Symfony\Component\Validator\Constraints as Assert;

class UserFilterQueryInterface implements ORMFilterQueryInterface
{
    #[Property(description: 'The query to search for')]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $query = null;

    #[\Override]
    public function applyFilter(QueryBuilder $queryBuilder): QueryBuilder
    {
        if ($this->query !== null && $this->query !== '' && $this->query !== '0') {
            $queryBuilder
                ->andWhere('entity.email LIKE :query')
                ->setParameter('query', "%{$this->query}%")
            ;
        }

        return $queryBuilder;
    }
}

```

Controller:

```php
<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Repository\UserRepository;use App\Trait\EntityManagerTrait;use App\User\Api\UserCollectionMeta;use App\User\Api\UserFilterQueryInterface;use App\User\Entity\UserEntity;use App\User\Serialization\UserGroups;use App\User\ValueObject\UserCollection;use Module\Api\Attribut\ApiRoute;use Module\Api\Attribut\OpenApiMeta;use Module\Api\Attribut\OpenApiResponse;use Module\Api\Dto\ApiResponse;use Module\Api\Enum\HttpMethodEnum;use Module\Api\Enum\ResponseTypeEnum;use Module\Api\Service\PaginatorService;use Symfony\Component\HttpKernel\Attribute\AsController;use Symfony\Component\HttpKernel\Attribute\MapQueryString;

/**
 * @see \App\Tests\User\Controller\GetCollectionUserControllerTest
 */
#[AsController]
#[ApiRoute('/api/users', httpMethodEnum: HttpMethodEnum::GET)]
class GetCollectionUserController
{
    use EntityManagerTrait;

    /**
     * @return ApiResponse<UserCollection, UserCollectionMeta>
     */
    #[OpenApiResponse(UserEntity::class, responseTypeEnum: ResponseTypeEnum::COLLECTION)]
    #[OpenApiMeta(UserCollectionMeta::class)]
    public function __invoke(
        PaginatorService $paginator, 
        #[MapQueryString(validationFailedStatusCode: 400)] ?UserFilterQueryInterface $filterQuery
    ): ApiResponse
    {
        /** @var UserRepository $entityRepository */
        $entityRepository = $this->em->getRepository(UserEntity::class);
        $queryBuilder = $entityRepository->createQueryBuilder('entity');

        $userCollection = $paginator->paginate($queryBuilder, UserCollection::class, [$filterQuery]);

        return new ApiResponse(data: $userCollection, apiMetadata: $userCollection->getMeta(), groups: [UserGroups::READ]);
    }
}
```

In that case the query url will be like this:

```http request
GET /api/users?query=user-email
```

As you can see is pretty simple to make a paginated request with filter, you just have to use the `#[MapQueryString]` attribute to map the query string
parameters to the filter object.

> [!NOTE]
>
> You can pass multiple filter objects to the `paginate` method, and the paginator will apply all the filters to the query builder.
>
> Take care of the order of the filters, because the paginator will apply the filters in the same order that you pass them to the `paginate` method.

Object properties are automatically validated by the `#[MapQueryString]` attribute, and if the validation fails, the request will return a 400 status code.

The status code can be changed by setting the `validationFailedStatusCode` parameter of the `#[MapQueryString]` attribute.

See Symfony documentation for more information
about [MapQueryString documentation](https://symfony.com/doc/current/controller.html#mapping-the-whole-query-string).