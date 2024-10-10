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
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
#[Group('integration')]
#[Group('controller')]
#[Group('budget')]
#[Group('budget-controller')]
final class DeleteExpenseCategoryControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/expense-categories';

    #[TestDox('When you call DELETE /api/expense-categories/{id}, it should delete the expense category')]
    #[Test]
    public function deleteExpenseCategoryController_WhenDataOk_ReturnsSuccessful(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $this->client->loginUser($user);

        $expenseCategory = ExpenseCategoryFactory::createOne();

        // ACT
        $response = $this->clientRequest(Request::METHOD_DELETE, self::API_ENDPOINT . '/' . $expenseCategory->getId());

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertSame(Response::HTTP_NO_CONTENT, $response);
    }
}
