<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Income\Payload\IncomeLinePayload;
use App\Dto\Income\Payload\IncomePayload;
use App\Dto\Income\Response\IncomeResponse;
use App\Entity\Income;
use App\Enum\IncomeTypes;
use App\Repository\IncomeLineRepository;
use App\Repository\IncomeRepository;
use App\Service\IncomeService;
use App\Tests\Common\Factory\IncomeFactory;
use My\RestBundle\Dto\PaginationQueryParams;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
use My\RestBundle\Test\Helper\PaginationTestHelper;
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

    private IncomeLineRepository $incomeLineRepository;

    private IncomeService $incomeService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->incomeRepository = $this->createMock(IncomeRepository::class);
        $this->incomeLineRepository = $this->createMock(IncomeLineRepository::class);

        $this->incomeService = new IncomeService($this->incomeRepository, $this->incomeLineRepository);
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

        $incomePayload = (new IncomePayload());

        $this->incomeRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Income $income): void {
                $income->setId(1);
            })
        ;

        // ACT
        $incomeResponse = $this->incomeService->create($incomePayload);

        // ASSERT
        $this->assertInstanceOf(IncomeResponse::class, $incomeResponse);
        $this->assertInstanceOf(Income::class, $income);
        $this->assertSame($income->getId(), $incomeResponse->getId());
    }

    #[TestDox('When calling update income, it should update and return the income updated')]
    #[Test]
    public function updateIncomeService_WhenDataOk_ReturnsIncomeUpdated(): void
    {
        // ARRANGE
        $income = IncomeFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $incomePayload = (new IncomePayload());

        $this->incomeRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Income $income): void {
                $income->setId(1);
            })
        ;

        // ACT
        $incomeResponse = $this->incomeService->update($incomePayload, $income);

        // ASSERT
        $this->assertInstanceOf(IncomeResponse::class, $incomeResponse);
        $this->assertInstanceOf(Income::class, $income);
        $this->assertSame($income->getId(), $incomeResponse->getId());
    }

    #[TestDox('When calling delete income, it should delete the income')]
    #[Test]
    public function deleteIncomeService_WhenDataOk_ReturnsNoContent(): void
    {
        // ARRANGE
        $income = IncomeFactory::new()->withoutPersisting()->create()->object();

        // ACT
        $incomeResponse = $this->incomeService->delete($income);

        // ASSERT
        $this->assertInstanceOf(Income::class, $income);
        $this->assertSame($income->getId(), $incomeResponse->getId());
    }

    #[TestDox('When you call paginate, it should return the incomes list')]
    #[Test]
    public function paginateIncomeService_WhenDataOk_ReturnsIncomesList(): void
    {
        // ARRANGE
        $incomes = IncomeFactory::new()->withoutPersisting()->createMany(20);
        $slidingPagination = PaginationTestHelper::getPagination($incomes);

        $this->incomeRepository->method('paginate')
            ->willReturn($slidingPagination)
        ;

        // ACT
        $incomesResponse = $this->incomeService->paginate(new PaginationQueryParams());

        // ASSERT
        $this->assertCount(20, $incomesResponse);
    }

    #[TestDox('When calling updateOrCreateIncome, it should returns the income response')]
    #[Test]
    public function updateOrCreateIncomeIncomeService_WhenDataOk_ReturnsIncomeResponse(): void
    {
        // ARRANGE PRIVATE METHOD TEST
        $incomeService = new IncomeService($this->incomeRepository, $this->incomeLineRepository);
        $method = $this->getPrivateMethod(IncomeService::class, 'updateOrCreateIncome');

        // ARRANGE
        $income = IncomeFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $incomeLinePayload = (new IncomeLinePayload())
            ->setId($income->getIncomeLines()[0]->getId())
            ->setAmount(100)
            ->setName('test')
            ->setType(IncomeTypes::SALARY)
        ;

        $incomeLinePayload2 = (new IncomeLinePayload())
            ->setId($income->getIncomeLines()[1]->getId())
            ->setAmount(200)
            ->setName('test2')
            ->setType(IncomeTypes::DIVIDENDS)
        ;

        $expenseLinesPayload = [$incomeLinePayload, $incomeLinePayload2];

        $incomePayload = (new IncomePayload())
            ->setIncomeLines($expenseLinesPayload)
        ;

        $this->incomeLineRepository->expects($this->exactly(2))
            ->method('find')
            ->willReturn($income->getIncomeLines()[0])
        ;

        $this->incomeRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Income $income): void {
                $income->setId(1);
            })
        ;

        // ACT
        $incomeResponse = $method->invoke($incomeService, $incomePayload, $income);

        // ASSERT
        $this->assertInstanceOf(IncomeResponse::class, $incomeResponse);
        $this->assertSame($income->getId(), $incomeResponse->getId());
    }

    private function getPrivateMethod(string $className, string $methodName): \ReflectionMethod
    {
        $reflectionClass = new \ReflectionClass($className);

        return $reflectionClass->getMethod($methodName);
    }
}
