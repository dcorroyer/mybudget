<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Budget;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Dto\Expense\Payload\ExpenseLinePayload;
use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Dto\Income\Payload\IncomePayload;
use App\Entity\ExpenseCategory;
use App\Entity\User;
use App\Repository\BudgetRepository;
use App\Service\BudgetService;
use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\ExpenseCategoryFactory;
use App\Tests\Common\Factory\ExpenseFactory;
use App\Tests\Common\Factory\IncomeFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('integration')]
#[Group('controller')]
#[Group('budget')]
#[Group('budget-controller')]
class CreateBudgetControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/budgets';

    private KernelBrowser $client;

    private BudgetService $budgetService;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->budgetService = $this->createMock(BudgetService::class);
        $budgetRepository = $this->createMock(BudgetRepository::class);

        $container = self::getContainer();
        $container->set(BudgetService::class, $this->budgetService);
        $container->set(BudgetRepository::class, $budgetRepository);
    }

    #[TestDox('When you call POST /api/budgets, it should create and return the budget')]
    #[Test]
    public function createBudgetController_WhenDataOk_ReturnsBudget(): void
    {
        $this->markTestSkipped();
        // ARRANGE
        $incomes = IncomeFactory::new()->withoutPersisting()->createMany(2);
        $expenses = ExpenseFactory::new()->withoutPersisting()->createMany(2);
        $expenseCategory = ExpenseCategoryFactory::new()->withoutPersisting()->create()->object();
        $budget = BudgetFactory::new()->withoutPersisting()->create()->object();

        $budgetPayload = [
            'date' => $budget->getDate()->format('Y-m'),
            'expenses' => [
                'category' => [
                    'name' => $expenseCategory->getName()
                ],
                'expenseLines' => [
                    [
                        'name' => $expenses[0]->getName(),
                        'amount' => $expenses[0]->getAmount()
                    ],
                    [
                        'name' => $expenses[1]->getName(),
                        'amount' => $expenses[1]->getAmount()
                    ]
                ]
            ],
            'incomes' => [
                [
                    'name' => $incomes[0]->getName(),
                    'amount' => $incomes[0]->getAmount()
                ],
                [
                    'name' => $incomes[1]->getName(),
                    'amount' => $incomes[1]->getAmount()
                ]
            ]
        ];

        //dd(json_encode($budgetPayload));

        $this->budgetService
            ->expects($this->once())
            ->method('create')
            ->willReturn($budget)
        ;

        // ACT
        $endpoint = self::API_ENDPOINT;

        $this->client->request(method: 'POST', uri: $endpoint, server: [
            'Content-Type' => 'application/json',
        ], content: json_encode($budgetPayload));
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertSame($budget->getId(), $data['id']);
    }
}
