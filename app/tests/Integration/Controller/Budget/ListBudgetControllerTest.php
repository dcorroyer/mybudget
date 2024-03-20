<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Budget;

use App\Entity\User;
use App\Repository\BudgetRepository;
use App\Service\BudgetService;
use App\Tests\Common\Factory\BudgetFactory;
use My\RestBundle\Test\Helper\PaginationTestHelper;
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
class ListBudgetControllerTest extends WebTestCase
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

    #[TestDox('When you call GET /api/budgets, it should return the budgets list')]
    #[Test]
    public function listBudgetController_WhenDataOk_ReturnsBudgets(): void
    {
        // ARRANGE
        $budgets = BudgetFactory::new()->withoutPersisting()->createMany(20);
        $slidingPagination = PaginationTestHelper::getPagination($budgets);

        $this->budgetService
            ->expects($this->once())
            ->method('paginate')
            ->willReturn($slidingPagination)
        ;

        $endpoint = self::API_ENDPOINT;

        // ACT
        $this->client->request(method: 'GET', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $content = json_decode($this->client->getResponse()->getContent(), true);

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertCount(20, $content['data']);
    }
}
