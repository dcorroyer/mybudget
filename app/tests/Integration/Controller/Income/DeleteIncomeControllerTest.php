<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Income;

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

/**
 * @internal
 */
#[Group('integration')]
#[Group('controller')]
#[Group('income')]
#[Group('income-controller')]
class DeleteIncomeControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/incomes';

    private KernelBrowser $client;

    private IncomeService $incomeService;

    private IncomeRepository $incomeRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->incomeService = $this->createMock(IncomeService::class);
        $this->incomeRepository = $this->createMock(IncomeRepository::class);

        $container = self::getContainer();
        $container->set(IncomeService::class, $this->incomeService);
        $container->set(IncomeRepository::class, $this->incomeRepository);
    }

    #[TestDox('When you call DELETE /api/incomes/{id}, it should update and return NoContent')]
    #[Test]
    public function deleteIncomeController_WhenDataOk_ReturnsNoContent(): void
    {
        // ARRANGE
        $income = IncomeFactory::new()->withoutPersisting()->create()->object();

        $this->incomeService
            ->expects($this->once())
            ->method('delete')
            ->willReturn($income)
        ;

        $this->incomeRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($income)
        ;

        // ACT
        $endpoint = self::API_ENDPOINT . '/' . $income->getId();
        $this->client->request(method: 'DELETE', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ],);
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(204);
        $this->assertEmpty($data);
    }
}
