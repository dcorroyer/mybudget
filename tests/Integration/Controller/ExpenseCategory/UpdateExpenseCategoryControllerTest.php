<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\ExpenseCategory;

use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Dto\ExpenseCategory\Response\ExpenseCategoryResponse;
use App\Entity\User;
use App\Repository\ExpenseCategoryRepository;
use App\Service\ExpenseCategoryService;
use App\Tests\Common\Factory\ExpenseCategoryFactory;
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
class UpdateExpenseCategoryControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/expenses-categories';

    private KernelBrowser $client;

    private ExpenseCategoryService $expenseCategoryService;

    private ExpenseCategoryRepository $expenseCategoryRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->expenseCategoryService = $this->createMock(ExpenseCategoryService::class);
        $this->expenseCategoryRepository = $this->createMock(ExpenseCategoryRepository::class);

        $container = self::getContainer();
        $container->set(ExpenseCategoryService::class, $this->expenseCategoryService);
        $container->set(ExpenseCategoryRepository::class, $this->expenseCategoryRepository);
    }

    #[TestDox('When you call PUT /api/expenses-categories/{id}, it should update and return the expense category')]
    #[Test]
    public function updateExpenseCategoryController_WhenDataOk_ReturnsExpenseCategory(): void
    {
        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::new()->withoutPersisting()->create()->object();

        $payload = (new ExpenseCategoryPayload())
            ->setName($expenseCategory->getName());

        $expenseResponse = (new ExpenseCategoryResponse())
            ->setId($expenseCategory->getId())
            ->setName($payload->getName());

        $this->expenseCategoryService
            ->expects($this->once())
            ->method('update')
            ->willReturn($expenseResponse);

        $this->expenseCategoryRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($expenseCategory);

        // ACT
        $endpoint = self::API_ENDPOINT;
        $this->client->request(
            method: 'PUT',
            uri: $endpoint . '/' . $expenseCategory->getId(),
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
        $this->assertEquals($payload->getName(), $data['name']);
    }
}
