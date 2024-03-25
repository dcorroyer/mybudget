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
class UpdateBudgetControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/budgets';

    private KernelBrowser $client;

    private BudgetService $budgetService;

    private BudgetRepository $budgetRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->budgetService = $this->createMock(BudgetService::class);
        $this->budgetRepository = $this->createMock(BudgetRepository::class);

        $container = self::getContainer();
        $container->set(BudgetService::class, $this->budgetService);
        $container->set(BudgetRepository::class, $this->budgetRepository);
    }

    #[TestDox('When you call PUT /api/budgets/{id}, it should update and return the budget')]
    #[Test]
    public function updateBudgetController_WhenDataOk_ReturnsBudget(): void
    {
        $this->markTestSkipped();
        // ARRANGE
        $budget = BudgetFactory::new()->withoutPersisting()->create()->object();

        $budgetPayload = (new BudgetPayload())
            ->setDate(new \DateTime('2022-01'))
        ;

        $this->budgetService
            ->expects($this->once())
            ->method('update')
            ->willReturn($budget)
        ;

        $this->budgetRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($budget)
        ;

        // ACT
        $endpoint = self::API_ENDPOINT . '/' . $budget->getId();
        $this->client->request(method: 'PUT', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($budgetPayload));
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertSame($budget->getId(), $data['id']);
    }
}
