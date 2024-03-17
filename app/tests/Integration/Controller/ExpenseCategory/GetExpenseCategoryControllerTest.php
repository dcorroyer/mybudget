<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\ExpenseCategory;

use App\Entity\User;
use App\Repository\ExpenseCategoryRepository;
use App\Tests\Common\Factory\ExpenseCategoryFactory;
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
#[Group('expense-category')]
#[Group('expense-category-controller')]
class GetExpenseCategoryControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/expenses-categories';

    private KernelBrowser $client;

    private ExpenseCategoryRepository $expenseCategoryRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->expenseCategoryRepository = $this->createMock(ExpenseCategoryRepository::class);

        $container = self::getContainer();
        $container->set(ExpenseCategoryRepository::class, $this->expenseCategoryRepository);
    }

    #[TestDox('When you call GET /api/expenses-categories/{id}, it should return the expense category')]
    #[Test]
    public function getExpenseCategoryController_WhenDataOk_ReturnsExpenseCategory(): void
    {
        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::new()->withoutPersisting()->create()->object();

        $this->expenseCategoryRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($expenseCategory)
        ;

        $endpoint = self::API_ENDPOINT . '/' . $expenseCategory->getId();

        // ACT
        $this->client->request(method: 'GET', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertSame($expenseCategory->getId(), $data['id']);
        $this->assertSame($expenseCategory->getName(), $data['name']);
    }
}
