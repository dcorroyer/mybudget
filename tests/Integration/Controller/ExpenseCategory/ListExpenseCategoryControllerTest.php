<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\ExpenseCategory;

use App\Entity\User;
use App\Repository\ExpenseCategoryRepository;
use App\Service\ExpenseCategoryService;
use App\Tests\Common\Factory\ExpenseCategoryFactory;
use My\RestBundle\Test\Helper\PaginationTestHelper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

#[Group('integration')]
#[Group('controller')]
#[Group('expense-category')]
#[Group('expense-category-controller')]
class ListExpenseCategoryControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/expenses-categories';

    private KernelBrowser $client;

    private ExpenseCategoryService $expenseCategoryService;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        // $this->client->loginUser(new User());

        $this->expenseCategoryService = $this->createMock(ExpenseCategoryService::class);
        $expenseCategoryRepository = $this->createMock(ExpenseCategoryRepository::class);

        $container = self::getContainer();
        $container->set(ExpenseCategoryService::class, $this->expenseCategoryService);
        $container->set(ExpenseCategoryRepository::class, $expenseCategoryRepository);
    }

    #[TestDox('When you call GET /api/expense-categories, it should return the expense categories list')]
    #[Test]
    public function listExpenseCategoryController_WhenDataOk_ReturnsExpenseCategories(): void
    {
        // ARRANGE
        $expenseCategories = ExpenseCategoryFactory::new()->withoutPersisting()->createMany(20);
        $pagination = PaginationTestHelper::getPagination($expenseCategories);

        $this->expenseCategoryService
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
