<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Tracking;

use App\Entity\User;
use App\Repository\TrackingRepository;
use App\Service\TrackingService;
use App\Tests\Common\Factory\TrackingFactory;
use My\RestBundle\Test\Helper\PaginationTestHelper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

#[Group('integration')]
#[Group('controller')]
#[Group('tracking')]
#[Group('tracking-controller')]
class ListTrackingControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/trackings';

    private KernelBrowser $client;

    private TrackingService $trackingService;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->trackingService = $this->createMock(TrackingService::class);
        $trackingRepository = $this->createMock(TrackingRepository::class);

        $container = self::getContainer();
        $container->set(TrackingService::class, $this->trackingService);
        $container->set(TrackingRepository::class, $trackingRepository);
    }

    #[TestDox('When you call GET /api/trackings, it should return the trackings list')]
    #[Test]
    public function listTrackingController_WhenDataOk_ReturnsTrackings(): void
    {
        // ARRANGE
        $trackings = TrackingFactory::new()->withoutPersisting()->createMany(20);
        $pagination = PaginationTestHelper::getPagination($trackings);

        $this->trackingService
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
