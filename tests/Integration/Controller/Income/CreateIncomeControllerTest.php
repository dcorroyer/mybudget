<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Income;

use App\Dto\Income\Payload\IncomePayload;
use App\Dto\Income\Response\IncomeResponse;
use App\Entity\User;
use App\Repository\IncomeRepository;
use App\Service\IncomeService;
use App\Tests\Common\Factory\IncomeFactory;
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
class CreateIncomeControllerTest extends WebTestCase
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

    #[TestDox('When you call POST /api/incomes, it should create and return the income')]
    #[Test]
    public function createIncomeController_WhenDataOk_ReturnsIncome(): void
    {
        // ARRANGE
        $income = IncomeFactory::new()->withoutPersisting()->create()->object();

        $payload = (new IncomePayload());

        $incomeResponse = (new IncomeResponse())
            ->setId($income->getId());

        $this->incomeService
            ->expects($this->once())
            ->method('create')
            ->willReturn($incomeResponse);

        // ACT
        $endpoint = self::API_ENDPOINT;
        $this->client->request(
            method: 'POST',
            uri: $endpoint,
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
        $this->assertEquals($incomeResponse->getId(), $data['id']);
    }
}
