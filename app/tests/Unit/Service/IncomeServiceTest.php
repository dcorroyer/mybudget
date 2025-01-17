<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Budget\Dto\Payload\IncomePayload;
use App\Budget\Entity\Income;
use App\Budget\Repository\IncomeRepository;
use App\Budget\Service\IncomeService;
use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\IncomeFactory;
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
final class IncomeServiceTest extends TestCase
{
    use Factories;

    private IncomeRepository $incomeRepository;

    private IncomeService $incomeService;

    #[\Override]
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
        $income = IncomeFactory::createOne([
            'id' => 1,
        ]);

        $budget = BudgetFactory::createOne();

        $incomePayload = (new IncomePayload());
        $incomePayload->name = $income->getName();
        $incomePayload->amount = $income->getAmount();

        $this->incomeRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Income $income): void {
                $income->setId(1);
            })
        ;

        // ACT
        $incomeResponse = $this->incomeService->create($incomePayload, $budget);

        // ASSERT
        self::assertInstanceOf(Income::class, $income);
        self::assertSame($income->getId(), $incomeResponse->getId());
        self::assertSame($income->getName(), $incomeResponse->getName());
        self::assertSame($income->getAmount(), $incomeResponse->getAmount());
    }
}
