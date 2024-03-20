<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Budget;

use App\Entity\User;
use App\Repository\BudgetRepository;
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
class GetBudgetControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/budgets';

    private KernelBrowser $client;

    private BudgetRepository $budgetRepository;

    private User $user;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->user = new User();
        $this->client->loginUser($this->user);

        $this->budgetRepository = $this->createMock(BudgetRepository::class);

        $container = self::getContainer();
        $container->set(BudgetRepository::class, $this->budgetRepository);
    }

    #[TestDox('When you call GET /api/budgets/{id}, it should return the budget')]
    #[Test]
    public function getBudgetController_WhenDataOk_ReturnsBudget(): void
    {
        // ARRANGE
        $budget = BudgetFactory::new([
            'user' => $this->user,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $this->budgetRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($budget)
        ;

        $endpoint = self::API_ENDPOINT . '/' . $budget->getId();

        // ACT
        $this->client->request(method: 'GET', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertSame($budget->getId(), $data['id']);
        $this->assertSame($budget->getDate()->format('Y-m'), $data['date']);
    }
}
