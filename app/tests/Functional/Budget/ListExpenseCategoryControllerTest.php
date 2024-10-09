<?php

declare(strict_types=1);

namespace App\Tests\Functional\Budget;

use App\Tests\Common\Factory\ExpenseCategoryFactory;
use App\Tests\Common\Factory\UserFactory;
use App\Tests\Functional\TestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
#[Group('integration')]
#[Group('controller')]
#[Group('budget')]
#[Group('budget-controller')]
final class ListExpenseCategoryControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/expense-categories';

    #[TestDox('When you call GET /api/expense-categories, it should returns the expense categories list')]
    #[Test]
    public function listExpenseCategoryController_WhenDataOk_ReturnsExpenseCategoriesList(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $this->client->loginUser($user);

        $expenseCategories = ExpenseCategoryFactory::createMany(20);

        // ACT
        $response = $this->clientRequest(Request::METHOD_GET, self::API_ENDPOINT);
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertCount(\count($expenseCategories), $responseData);
    }
}
