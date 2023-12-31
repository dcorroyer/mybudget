<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Tracking\Payload\TrackingPayload;
use App\Dto\Tracking\Payload\UpdateTrackingPayload;
use App\Dto\Tracking\Response\TrackingResponse;
use App\Entity\Tracking;
use App\Repository\ExpenseRepository;
use App\Repository\IncomeRepository;
use App\Repository\TrackingRepository;
use App\Service\TrackingService;
use App\Tests\Common\Factory\TrackingFactory;
use My\RestBundle\Dto\PaginationQueryParams;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
use My\RestBundle\Test\Helper\PaginationTestHelper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Zenstruck\Foundry\Test\Factories;

#[Group('unit')]
#[Group('service')]
#[Group('tracking')]
#[Group('tracking-service')]
class TrackingServiceTest extends TestCase
{
    use SerializerTrait;
    use Factories;

    private TrackingRepository $trackingRepository;

    private IncomeRepository $incomeRepository;

    private ExpenseRepository $expenseRepository;

    private TrackingService $trackingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trackingRepository = $this->createMock(TrackingRepository::class);
        $this->incomeRepository = $this->createMock(IncomeRepository::class);
        $this->expenseRepository = $this->createMock(ExpenseRepository::class);

        $this->trackingService = new TrackingService(
            $this->trackingRepository,
            $this->incomeRepository,
            $this->expenseRepository
        );
    }

    #[TestDox('When calling create tracking, it should create and return a new tracking')]
    #[Test]
    public function createTrackingService_WhenDataOk_ReturnsTracking()
    {
        // ARRANGE
        $tracking = TrackingFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object();

        $trackingPayload = (new TrackingPayload())
            ->setDate($tracking->getDate())
            ->setIncomeId($tracking->getIncome()->getId())
            ->setExpenseId($tracking->getExpense()->getId());

        $this->incomeRepository->expects($this->once())
            ->method('find')
            ->willReturn($tracking->getIncome());

        $this->expenseRepository->expects($this->once())
            ->method('find')
            ->willReturn($tracking->getExpense());

        $this->trackingRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Tracking $tracking) {
                $tracking->setId(1)
                    ->updateName();
            });

        // ACT
        $trackingResponse = $this->trackingService->create($trackingPayload);

        // ASSERT
        $this->assertInstanceOf(TrackingResponse::class, $trackingResponse);
        $this->assertInstanceOf(Tracking::class, $tracking);
        $this->assertEquals($tracking->getId(), $trackingResponse->getId());
    }

    #[TestDox('When calling update tracking, it should update and return the tracking updated')]
    #[Test]
    public function updateTrackingService_WhenDataOk_ReturnsTrackingUpdated()
    {
        // ARRANGE
        $tracking = TrackingFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object();

        $trackingPayload = (new UpdateTrackingPayload())
            ->setDate(new \DateTime('2022-01'));

        $this->trackingRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Tracking $tracking) {
                $tracking->setId(1)
                    ->updateName();
            });

        // ACT
        $trackingResponse = $this->trackingService->update($trackingPayload, $tracking);

        // ASSERT
        $this->assertInstanceOf(TrackingResponse::class, $trackingResponse);
        $this->assertInstanceOf(Tracking::class, $tracking);
        $this->assertEquals($tracking->getId(), $trackingResponse->getId());
    }

    #[TestDox('When calling delete tracking, it should delete the tracking')]
    #[Test]
    public function deleteTrackingService_WhenDataOk_ReturnsNoContent()
    {
        // ARRANGE
        $tracking = TrackingFactory::new()->withoutPersisting()->create()->object();

        // ACT
        $trackingResponse = $this->trackingService->delete($tracking);

        // ASSERT
        $this->assertInstanceOf(Tracking::class, $tracking);
        $this->assertEquals($tracking->getId(), $trackingResponse->getId());
    }

    #[TestDox('When you call paginate, it should return the trackings list')]
    #[Test]
    public function paginateTrackingService_WhenDataOk_ReturnsTrackingsList()
    {
        // ARRANGE
        $trackings = TrackingFactory::new()->withoutPersisting()->createMany(20);
        $pagination = PaginationTestHelper::getPagination($trackings);

        $this->trackingRepository->method('paginate')
            ->willReturn($pagination);

        // ACT
        $trackingsResponse = $this->trackingService->paginate(new PaginationQueryParams());

        // ASSERT
        $this->assertCount(20, $trackingsResponse);
    }

    #[TestDox('When calling trackingResponse, it should returns the tracking response')]
    #[Test]
    public function trackingResponseTrackingService_WhenDataContainsNewName_ReturnsTrackingResponse()
    {
        // ARRANGE PRIVATE METHOD TEST
        $object = new TrackingService($this->trackingRepository, $this->incomeRepository, $this->expenseRepository);
        $method = $this->getPrivateMethod(TrackingService::class, 'trackingResponse');

        // ARRANGE
        $tracking = TrackingFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object();

        $tracking->updateName();

        // ACT
        $trackingResponse = $method->invoke($object, $tracking);

        // ASSERT
        $this->assertInstanceOf(TrackingResponse::class, $trackingResponse);
        $this->assertEquals($tracking->getId(), $trackingResponse->getId());
    }

    private function getPrivateMethod($className, $methodName): \ReflectionMethod
    {
        $reflector = new \ReflectionClass($className);

        return $reflector->getMethod($methodName);
    }
}
