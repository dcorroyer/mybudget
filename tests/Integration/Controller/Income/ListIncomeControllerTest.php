<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Income;

use App\Entity\User;
use App\Repository\IncomeRepository;
use App\Service\IncomeService;
use App\Tests\Common\Factory\IncomeFactory;
use My\RestBundle\Test\Helper\PaginationTestHelper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

#[Group('integration')]
#[Group('controller')]
#[Group('income')]
#[Group('income-controller')]
class ListIncomeControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/incomes';

    private KernelBrowser $client;

    private IncomeService $incomeService;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->incomeService = $this->createMock(IncomeService::class);
        $incomeRepository = $this->createMock(IncomeRepository::class);

        $container = self::getContainer();
        $container->set(IncomeService::class, $this->incomeService);
        $container->set(IncomeRepository::class, $incomeRepository);
    }

    #[TestDox('When you call GET /api/incomes, it should return the incomes list')]
    #[Test]
    public function list_WhenDataOk_ReturnsIncomes(): void
    {
        // ARRANGE
        $incomes = IncomeFactory::new()->withoutPersisting()->createMany(20);
        $pagination = PaginationTestHelper::getPagination($incomes);

        $this->incomeService
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
