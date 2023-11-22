<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Expense;

use App\Entity\User;
use App\Repository\ExpenseRepository;
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
class GetExpenseControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/expenses';

    private KernelBrowser $client;

    private ExpenseRepository $expenseRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        // $this->client->loginUser(new User());

        $this->expenseRepository = $this->createMock(ExpenseRepository::class);

        $container = self::getContainer();
        $container->set(ExpenseRepository::class, $this->expenseRepository);
    }

    #[TestDox('When you call GET /api/expenses/{id}, it should return the expense')]
    #[Test]
    public function getExpenseController_WhenDataOk_ReturnsExpense(): void
    {
        // ARRANGE
        $expense = ExpenseFactory::new()->withoutPersisting()->create()->object();

        $this->expenseRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($expense);

        $endpoint = self::API_ENDPOINT . '/' . $expense->getId();

        // ACT
        $this->client->request(method: 'GET', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertEquals($expense->getId(), $data['id']);
        $this->assertEquals($expense->getAmount(), $data['amount']);
        $this->assertEquals($expense->getDate()->format('Y-m-d'), $data['date']);
    }
}
