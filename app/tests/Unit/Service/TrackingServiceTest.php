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
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('service')]
#[Group('tracking')]
#[Group('tracking-service')]
class TrackingServiceTest extends TestCase
{
    use Factories;
    use SerializerTrait;

    private TrackingRepository $trackingRepository;

    private IncomeRepository $incomeRepository;

    private ExpenseRepository $expenseRepository;

    private TrackingService $trackingService;

    private Security $security;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trackingRepository = $this->createMock(TrackingRepository::class);
        $this->incomeRepository = $this->createMock(IncomeRepository::class);
        $this->expenseRepository = $this->createMock(ExpenseRepository::class);
        $this->security = $this->createMock(Security::class);

        $this->trackingService = new TrackingService(
            trackingRepository: $this->trackingRepository,
            incomeRepository: $this->incomeRepository,
            expenseRepository: $this->expenseRepository,
            security: $this->security,
        );
    }

    #[TestDox('When calling create tracking, it should create and return a new tracking')]
    #[Test]
    public function createTrackingService_WhenDataOk_ReturnsTracking(): void
    {
        // ARRANGE
        $tracking = TrackingFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $trackingPayload = (new TrackingPayload())
            ->setDate($tracking->getDate())
            ->setIncomeId($tracking->getIncome()->getId())
            ->setExpenseId($tracking->getExpense()->getId())
        ;

        $this->incomeRepository->expects($this->once())
            ->method('find')
            ->willReturn($tracking->getIncome())
        ;

        $this->expenseRepository->expects($this->once())
            ->method('find')
            ->willReturn($tracking->getExpense())
        ;

        $this->trackingRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Tracking $tracking): void {
                $tracking->setId(1)
                    ->updateName()
                ;
            })
        ;

        // ACT
        $trackingResponse = $this->trackingService->create($trackingPayload);

        // ASSERT
        $this->assertInstanceOf(TrackingResponse::class, $trackingResponse);
        $this->assertInstanceOf(Tracking::class, $tracking);
        $this->assertSame($tracking->getId(), $trackingResponse->getId());
    }

    #[TestDox('When calling create tracking without income or expense, it should throw an InvalidArgumentException')]
    #[Test]
    public function createTrackingService_WhenBadData_ReturnsInvalidArgumentException(): void
    {
        // ASSERT
        $this->expectException(\InvalidArgumentException::class);

        // ARRANGE
        $tracking = TrackingFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $trackingPayload = (new TrackingPayload())
            ->setDate($tracking->getDate())
            ->setIncomeId($tracking->getIncome()->getId())
            ->setExpenseId($tracking->getExpense()->getId())
        ;

        $this->incomeRepository->expects($this->once())
            ->method('find')
            ->willReturn(null)
        ;

        $this->expenseRepository->expects($this->once())
            ->method('find')
            ->willReturn(null)
        ;

        // ACT
        $this->trackingService->create($trackingPayload);
    }

    #[TestDox('When calling update tracking, it should update and return the tracking updated')]
    #[Test]
    public function updateTrackingService_WhenDataOk_ReturnsTrackingUpdated(): void
    {
        // ARRANGE
        $tracking = TrackingFactory::new([
            'id' => 1,
            'user' => $this->security->getUser(),
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $updateTrackingPayload = (new UpdateTrackingPayload())
            ->setDate(new \DateTime('2022-01'))
        ;

        $this->trackingRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Tracking $tracking): void {
                $tracking->setId(1)
                    ->updateName()
                ;
            })
        ;

        // ACT
        $trackingResponse = $this->trackingService->update($updateTrackingPayload, $tracking);

        // ASSERT
        $this->assertInstanceOf(TrackingResponse::class, $trackingResponse);
        $this->assertInstanceOf(Tracking::class, $tracking);
        $this->assertSame($tracking->getId(), $trackingResponse->getId());
    }

    #[TestDox('When calling update tracking with bad user, it should returns access denied exception')]
    #[Test]
    public function updateTrackingService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);

        // ARRANGE
        $tracking = TrackingFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $updateTrackingPayload = (new UpdateTrackingPayload())
            ->setDate(new \DateTime('2022-01'))
        ;

        // ACT
        $this->trackingService->update($updateTrackingPayload, $tracking);
    }

    #[TestDox('When calling get tracking, it should get the tracking')]
    #[Test]
    public function getTrackingService_WhenDataOk_ReturnsTracking(): void
    {
        // ARRANGE
        $tracking = TrackingFactory::new([
            'user' => $this->security->getUser(),
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $this->trackingRepository->expects($this->once())
            ->method('find')
            ->willReturn($tracking)
        ;

        // ACT
        $trackingResponse = $this->trackingService->get($tracking->getId());

        // ASSERT
        $this->assertInstanceOf(Tracking::class, $tracking);
        $this->assertSame($tracking->getId(), $trackingResponse->getId());
    }

    #[TestDox('When calling get tracking with bad id, it should throw not found exception')]
    #[Test]
    public function getTrackingService_WithBadId_ReturnsNotFoundException(): void
    {
        // ASSERT
        $this->expectException(NotFoundHttpException::class);

        // ACT
        $this->trackingService->get(999);
    }

    #[TestDox('When calling get tracking for another user, it should throw access denied exception')]
    #[Test]
    public function getTrackingService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);

        // ARRANGE
        $tracking = TrackingFactory::new()->withoutPersisting()->create()->object();

        $this->trackingRepository->expects($this->once())
            ->method('find')
            ->willReturn($tracking)
        ;

        // ACT
        $this->trackingService->get($tracking->getId());
    }

    #[TestDox('When calling delete tracking, it should delete the tracking')]
    #[Test]
    public function deleteTrackingService_WhenDataOk_ReturnsNoContent(): void
    {
        // ARRANGE
        $tracking = TrackingFactory::new([
            'user' => $this->security->getUser(),
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        // ACT
        $trackingResponse = $this->trackingService->delete($tracking);

        // ASSERT
        $this->assertInstanceOf(Tracking::class, $tracking);
        $this->assertSame($tracking->getId(), $trackingResponse->getId());
    }

    #[TestDox('When calling delete tracking with bad user, it should returns access denied exception')]
    #[Test]
    public function deleteTrackingService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);

        // ARRANGE
        $tracking = TrackingFactory::new()->withoutPersisting()->create()->object();

        // ACT
        $this->trackingService->delete($tracking);
    }

    #[TestDox('When you call paginate, it should return the trackings list')]
    #[Test]
    public function paginateTrackingService_WhenDataOk_ReturnsTrackingsList(): void
    {
        // ARRANGE
        $trackings = TrackingFactory::new()->withoutPersisting()->createMany(20);
        $slidingPagination = PaginationTestHelper::getPagination($trackings);

        $this->trackingRepository->method('paginate')
            ->willReturn($slidingPagination)
        ;

        // ACT
        $trackingsResponse = $this->trackingService->paginate(new PaginationQueryParams());

        // ASSERT
        $this->assertCount(20, $trackingsResponse);
    }

    #[TestDox('When calling trackingResponse, it should returns the tracking response')]
    #[Test]
    public function trackingResponseTrackingService_WhenDataContainsNewName_ReturnsTrackingResponse(): void
    {
        // ARRANGE PRIVATE METHOD TEST
        $trackingService = new TrackingService($this->trackingRepository, $this->incomeRepository, $this->expenseRepository, $this->security);
        $method = $this->getPrivateMethod(TrackingService::class, 'trackingResponse');

        // ARRANGE
        $tracking = TrackingFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $tracking->updateName();

        // ACT
        $trackingResponse = $method->invoke($trackingService, $tracking);

        // ASSERT
        $this->assertInstanceOf(TrackingResponse::class, $trackingResponse);
        $this->assertSame($tracking->getId(), $trackingResponse->getId());
    }

    #[TestDox('When calling checkAccess, it should returns an AccessDeniedException')]
    #[Test]
    public function checkAccessTrackingService_WhenBadData_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);

        // ARRANGE PRIVATE METHOD TEST
        $trackingService = new TrackingService($this->trackingRepository, $this->incomeRepository, $this->expenseRepository, $this->security);
        $method = $this->getPrivateMethod(TrackingService::class, 'checkAccess');

        // ARRANGE
        $tracking = TrackingFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        // ACT
        $method->invoke($trackingService, $tracking);
    }

    private function getPrivateMethod(string $className, string $methodName): \ReflectionMethod
    {
        $reflectionClass = new \ReflectionClass($className);

        return $reflectionClass->getMethod($methodName);
    }
}
