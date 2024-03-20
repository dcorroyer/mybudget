<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Expense;

use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\Expense\Response\ExpenseResponse;
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

/**
 * @internal
 */
#[Group('integration')]
#[Group('controller')]
#[Group('expense')]
#[Group('expense-controller')]
class CreateExpenseControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/expenses';

    private KernelBrowser $client;

    private ExpenseService $expenseService;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->expenseService = $this->createMock(ExpenseService::class);
        $expenseRepository = $this->createMock(ExpenseRepository::class);

        $container = self::getContainer();
        $container->set(ExpenseService::class, $this->expenseService);
        $container->set(ExpenseRepository::class, $expenseRepository);
    }

    #[TestDox('When you call POST /api/expenses, it should create and return the expense')]
    #[Test]
    public function createExpenseController_WhenDataOk_ReturnsExpense(): void
    {
        // ARRANGE
        $expense = ExpenseFactory::new()->withoutPersisting()->create()->object();

        $expensePayload = (new ExpensePayload())
            ->setExpenseLines($expense->getExpenseLines()->toArray())
        ;

        $expenseResponse = (new ExpenseResponse())
            ->setId($expense->getId())
        ;

        $this->expenseService
            ->expects($this->once())
            ->method('create')
            ->willReturn($expenseResponse)
        ;

        // ACT
        $endpoint = self::API_ENDPOINT;
        $this->client->request(method: 'POST', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($expensePayload));
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertSame($expenseResponse->getId(), $data['id']);
    }
}
