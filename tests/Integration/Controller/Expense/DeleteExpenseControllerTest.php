<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Expense;

use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Service\ExpenseService;
use App\Tests\Common\Factory\ExpenseFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

#[Group('integration')]
#[Group('controller')]
#[Group('expense')]
#[Group('expense-controller')]
class DeleteExpenseControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/expenses';

    private KernelBrowser $client;

    private ExpenseService $expenseService;

    private ExpenseRepository $expenseRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->expenseService = $this->createMock(ExpenseService::class);
        $this->expenseRepository = $this->createMock(ExpenseRepository::class);

        $container = self::getContainer();
        $container->set(ExpenseService::class, $this->expenseService);
        $container->set(ExpenseRepository::class, $this->expenseRepository);
    }

    #[TestDox('When you call DELETE /api/expenses/{id}, it should update and return NoContent')]
    #[Test]
    public function deleteExpenseController_WhenDataOk_ReturnsNoContent(): void
    {
        // ARRANGE
        $expense = ExpenseFactory::new()->withoutPersisting()->create()->object();

        $this->expenseService
            ->expects($this->once())
            ->method('delete')
            ->willReturn($expense);

        $this->expenseRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($expense);

        // ACT
        $endpoint = self::API_ENDPOINT . '/' . $expense->getId();
        $this->client->request(
            method: 'DELETE',
            uri: $endpoint,
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
        );
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(204);
        $this->assertEmpty($data);
    }
}
