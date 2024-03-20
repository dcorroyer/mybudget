<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Budget;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Dto\Budget\Response\BudgetResponse;
use App\Entity\User;
use App\Repository\BudgetRepository;
use App\Service\BudgetService;
use App\Tests\Common\Factory\BudgetFactory;
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
        // ARRANGE
        $budget = BudgetFactory::new()->withoutPersisting()->create()->object();

        $budgetPayload = (new BudgetPayload())
            ->setDate($budget->getDate())
            ->setExpenseId($budget->getExpense()->getId())
            ->setIncomeId($budget->getIncome()->getId())
        ;

        $budgetResponse = (new BudgetResponse())
            ->setId($budget->getId())
        ;

        $this->budgetService
            ->expects($this->once())
            ->method('create')
            ->willReturn($budgetResponse)
        ;

        // ACT
        $endpoint = self::API_ENDPOINT;
        $this->client->request(method: 'POST', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($budgetPayload));
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertSame($budgetResponse->getId(), $data['id']);
    }
}
