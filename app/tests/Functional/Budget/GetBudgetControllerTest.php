<?php

declare(strict_types=1);

namespace App\Tests\Functional\Budget;

use App\Tests\Common\Factory\BudgetFactory;
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
final class GetBudgetControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/budgets';

    #[TestDox('When you call GET /api/budgets/{id}, it should returns the budget')]
    #[Test]
    public function getBudgetController_WhenDataOk_ReturnsBudget(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $this->client->loginUser($user);

        $budget = BudgetFactory::createOne([
            'user' => $user,
        ]);

        // ACT
        $response = $this->clientRequest(Request::METHOD_GET, self::API_ENDPOINT . '/' . $budget->getId());
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertSame($budget->getId(), $responseData['id']);
    }
}
