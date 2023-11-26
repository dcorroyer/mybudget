<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Expense;

use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Service\ExpenseService;
use App\Tests\Common\Factory\ExpenseFactory;
use My\RestBundle\Test\Helper\PaginationTestHelper;
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
class ListExpenseControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/expenses';

    private KernelBrowser $client;

    private ExpenseService $expenseService;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        // $this->client->loginUser(new User());

        $this->expenseService = $this->createMock(ExpenseService::class);
        $expenseRepository = $this->createMock(ExpenseRepository::class);

        $container = self::getContainer();
        $container->set(ExpenseService::class, $this->expenseService);
        $container->set(ExpenseRepository::class, $expenseRepository);
    }

    #[TestDox('When you call GET /api/expenses, it should return the expenses list')]
    #[Test]
    public function listExpenseController_WhenDataOk_ReturnsExpenses(): void
    {
        // ARRANGE
        $expenses = ExpenseFactory::new()->withoutPersisting()->createMany(20);
        $pagination = PaginationTestHelper::getPagination($expenses);

        $this->expenseService
            ->expects($this->once())
            ->method('paginate')
            ->willReturn($pagination);

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
