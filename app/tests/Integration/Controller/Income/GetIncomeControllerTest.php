<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Income;

use App\Entity\User;
use App\Repository\IncomeRepository;
use App\Tests\Common\Factory\IncomeFactory;
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
#[Group('income')]
#[Group('income-controller')]
class GetIncomeControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/incomes';

    private KernelBrowser $client;

    private IncomeRepository $incomeRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->incomeRepository = $this->createMock(IncomeRepository::class);

        $container = self::getContainer();
        $container->set(IncomeRepository::class, $this->incomeRepository);
    }

    #[TestDox('When you call GET /api/incomes/{id}, it should return the income')]
    #[Test]
    public function getIncomeController_WhenDataOk_ReturnsIncome(): void
    {
        // ARRANGE
        $income = IncomeFactory::new()->withoutPersisting()->create()->object();

        $this->incomeRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($income)
        ;

        $endpoint = self::API_ENDPOINT . '/' . $income->getId();

        // ACT
        $this->client->request(method: 'GET', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertSame($income->getId(), $data['id']);
        $this->assertSame($income->getAmount(), $data['amount']);
    }
}
