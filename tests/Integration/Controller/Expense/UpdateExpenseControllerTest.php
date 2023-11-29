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

#[Group('integration')]
#[Group('controller')]
#[Group('expense')]
#[Group('expense-controller')]
class UpdateExpenseControllerTest extends WebTestCase
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

    #[TestDox('When you call PUT /api/expenses/{id}, it should update and return the expense')]
    #[Test]
    public function updateExpenseController_WhenDataOk_ReturnsExpense(): void
    {
        // ARRANGE
        $expense = ExpenseFactory::new()->withoutPersisting()->create()->object();

        $payload = (new ExpensePayload())
            ->setExpenseLines($expense->getExpenseLines()->toArray());

        $expenseResponse = (new ExpenseResponse())
            ->setId($expense->getId());

        $this->expenseService
            ->expects($this->once())
            ->method('update')
            ->willReturn($expenseResponse);

        $this->expenseRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($expense);

        // ACT
        $endpoint = self::API_ENDPOINT;
        $this->client->request(
            method: 'PUT',
            uri: $endpoint . '/' . $expense->getId(),
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode($payload)
        );
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertEquals($expenseResponse->getId(), $data['id']);
    }
}
