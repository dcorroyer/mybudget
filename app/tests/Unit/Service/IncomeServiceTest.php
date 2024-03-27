<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Income\Payload\IncomePayload;
use App\Entity\Income;
use App\Repository\IncomeRepository;
use App\Service\IncomeService;
use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\IncomeFactory;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('service')]
#[Group('income')]
#[Group('income-service')]
class IncomeServiceTest extends TestCase
{
    use Factories;
    use SerializerTrait;

    private IncomeRepository $incomeRepository;

    private IncomeService $incomeService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->incomeRepository = $this->createMock(IncomeRepository::class);

        $this->incomeService = new IncomeService($this->incomeRepository);
    }

    #[TestDox('When calling create income, it should create and return a new income')]
    #[Test]
    public function createIncomeService_WhenDataOk_ReturnsIncome(): void
    {
        // ARRANGE
        $income = IncomeFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $budget = BudgetFactory::new()->withoutPersisting()->create()->object();

        $incomePayload = (new IncomePayload())
            ->setName($income->getName())
            ->setAmount($income->getAmount())
        ;

        $this->incomeRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Income $income): void {
                $income->setId(1);
            })
        ;

        // ACT
        $incomeResponse = $this->incomeService->create($incomePayload, $budget);

        // ASSERT
        $this->assertInstanceOf(Income::class, $incomeResponse);
        $this->assertInstanceOf(Income::class, $income);
        $this->assertSame($income->getId(), $incomeResponse->getId());
        $this->assertSame($income->getName(), $incomeResponse->getName());
        $this->assertSame($income->getAmount(), $incomeResponse->getAmount());
    }
}
