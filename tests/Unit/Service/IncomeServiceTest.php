<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Income\Payload\IncomePayload;
use App\Dto\Income\Response\IncomeResponse;
use App\Entity\Income;
use App\Repository\IncomeRepository;
use App\Service\IncomeService;
use App\Tests\Common\Factory\IncomeFactory;
use My\RestBundle\Dto\PaginationQueryParams;
use My\RestBundle\Helper\DtoToEntityHelper;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
use My\RestBundle\Test\Helper\PaginationTestHelper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Zenstruck\Foundry\Test\Factories;

#[Group('unit')]
#[Group('service')]
#[Group('income')]
#[Group('income-service')]
class IncomeServiceTest extends TestCase
{
    use SerializerTrait;
    use Factories;

    private IncomeRepository $incomeRepository;

    private IncomeService $incomeService;

    private DtoToEntityHelper $dtoToEntityHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->incomeRepository = $this->createMock(IncomeRepository::class);
        $this->dtoToEntityHelper = $this->createMock(DtoToEntityHelper::class);

        $this->incomeService = new IncomeService($this->incomeRepository, $this->dtoToEntityHelper);
    }

    #[TestDox('When calling create income, it should create and return a new income')]
    #[Test]
    public function create_WhenDataOk_ReturnsIncome()
    {
        // ARRANGE
        $incomePayload = new IncomePayload();
        $income = IncomeFactory::new()->withoutPersisting()->create()->object();

        $this->dtoToEntityHelper->expects($this->once())
            ->method('create')
            ->willReturn($income);

        // ACT
        $incomeResponse = $this->incomeService->create($incomePayload);

        // ASSERT
        $this->assertInstanceOf(IncomeResponse::class, $incomeResponse);
        $this->assertInstanceOf(Income::class, $income);
        $this->assertEquals($income->getId(), $incomeResponse->getId());
        $this->assertEquals($income->getName(), $incomeResponse->getName());
        $this->assertEquals($income->getAmount(), $incomeResponse->getAmount());
        $this->assertEquals($income->getDate(), $incomeResponse->getDate());
        $this->assertEquals($income->getType(), $incomeResponse->getType());
    }

    #[TestDox('When calling update income, it should update and return the income updated')]
    #[Test]
    public function update_WhenDataOk_ReturnsIncomeUpdated()
    {
        // ARRANGE
        $incomePayload = new IncomePayload();
        $income = IncomeFactory::new()->withoutPersisting()->create()->object();

        $this->dtoToEntityHelper->expects($this->once())
            ->method('update')
            ->willReturn($income);

        // ACT
        $incomeResponse = $this->incomeService->update($incomePayload, $income);

        // ASSERT
        $this->assertInstanceOf(IncomeResponse::class, $incomeResponse);
        $this->assertInstanceOf(Income::class, $income);
        $this->assertEquals($income->getId(), $incomeResponse->getId());
        $this->assertEquals($income->getName(), $incomeResponse->getName());
        $this->assertEquals($income->getAmount(), $incomeResponse->getAmount());
        $this->assertEquals($income->getDate(), $incomeResponse->getDate());
        $this->assertEquals($income->getType(), $incomeResponse->getType());
    }

    #[TestDox('When calling delete income, it should delete the income')]
    #[Test]
    public function delete_WhenDataOk_ReturnsNoContent()
    {
        // ARRANGE
        $income = IncomeFactory::new()->withoutPersisting()->create()->object();

        // ACT
        $incomeResponse = $this->incomeService->delete($income);

        // ASSERT
        $this->assertInstanceOf(Income::class, $income);
        $this->assertEquals($income->getId(), $incomeResponse->getId());
    }

    #[TestDox('When you call paginate, it should return the incomes list')]
    #[Test]
    public function paginate_WhenDataOk_ReturnsIncomesList()
    {
        // ARRANGE
        $incomes = IncomeFactory::new()->withoutPersisting()->createMany(20);
        $pagination = PaginationTestHelper::getPagination($incomes);

        $this->incomeRepository->method('paginate')
            ->willReturn($pagination);

        // ACT
        $incomesResponse = $this->incomeService->paginate(new PaginationQueryParams());

        // ASSERT
        $this->assertCount(20, $incomesResponse);
    }
}
