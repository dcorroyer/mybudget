<?php

declare(strict_types=1);

namespace App\Tests\Functional\Budget;

use App\Tests\Common\Factory\BudgetFactory;
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
final class UpdateBudgetControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/budgets';

    #[TestDox('When you call PUT /api/budgets, it should update and return the budget')]
    #[Test]
    public function updateBudgetController_WhenDataOk_ReturnsBudget(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $budget = BudgetFactory::createOne([
            'user' => $user,
        ])->_real();

        $this->client->loginUser($user);

        $category = ExpenseCategoryFactory::createOne()->_real();

        $budgetPayload = [
            'date' => '2024-02',
            'incomes' => [
                [
                    'name' => 'Salaire',
                    'amount' => 2500,
                ],
                [
                    'name' => 'Prime',
                    'amount' => 500,
                ],
            ],
            'expenses' => [
                [
                    'name' => 'Loyer',
                    'amount' => 1000,
                    'expenseCategoryId' => $category->getId(),
                ],
                [
                    'name' => 'Electricité',
                    'amount' => 100,
                    'expenseCategoryId' => $category->getId(),
                ],
                [
                    'name' => 'Courses',
                    'amount' => 200,
                    'expenseCategoryId' => $category->getId(),
                ],
                [
                    'name' => 'Téléphone',
                    'amount' => 20,
                    'expenseCategoryId' => $category->getId(),
                ],
            ],
        ];

        // ACT
        $response = $this->clientRequest(
            Request::METHOD_PUT,
            self::API_ENDPOINT . '/' . $budget->getId(),
            $budgetPayload
        );
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');
        self::assertSame('Budget 2024-02', $responseData['name']);
        self::assertSame(1680, $responseData['savingCapacity']);
    }
}
