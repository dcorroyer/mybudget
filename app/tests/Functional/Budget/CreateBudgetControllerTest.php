<?php

declare(strict_types=1);

namespace App\Tests\Functional\Budget;

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
class CreateBudgetControllerTest extends TestBase
{
    private const API_ENDPOINT = '/api/budgets';

    #[TestDox('When you call POST /api/budgets, it should create and return the budget')]
    #[Test]
    public function createBudgetController_WhenDataOk_ReturnsBudget(): void
    {
        // ARRANGE
        $user = UserFactory::new()->create()->object();
        $this->client->loginUser($user);

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
                    'category' => [
                        'name' => 'Habitations',
                    ],
                    'expenseLines' => [
                        [
                            'name' => 'Loyer',
                            'amount' => 1000,
                        ],
                        [
                            'name' => 'Electricité',
                            'amount' => 100,
                        ],
                    ],
                ],
                [
                    'category' => [
                        'name' => 'Abonnements',
                    ],
                    'expenseLines' => [
                        [
                            'name' => 'Internet',
                            'amount' => 50,
                        ],
                        [
                            'name' => 'Mobile',
                            'amount' => 30,
                        ],
                    ],
                ],
            ],
        ];

        // ACT
        $response = $this->clientRequest(Request::METHOD_POST, self::API_ENDPOINT, $budgetPayload);
        $responseData = $response['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertSame('Budget 2024-02', $responseData['name']);
        $this->assertSame(1820, $responseData['savingCapacity']);
    }
}
