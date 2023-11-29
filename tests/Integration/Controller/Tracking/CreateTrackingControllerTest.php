<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Tracking;

use App\Dto\Tracking\Payload\TrackingPayload;
use App\Dto\Tracking\Response\TrackingResponse;
use App\Entity\User;
use App\Repository\TrackingRepository;
use App\Service\TrackingService;
use App\Tests\Common\Factory\TrackingFactory;
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
class CreateTrackingControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/trackings';

    private KernelBrowser $client;

    private TrackingService $trackingService;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        // $this->client->loginUser(new User());

        $this->trackingService = $this->createMock(TrackingService::class);
        $trackingRepository = $this->createMock(TrackingRepository::class);

        $container = self::getContainer();
        $container->set(TrackingService::class, $this->trackingService);
        $container->set(TrackingRepository::class, $trackingRepository);
    }

    #[TestDox('When you call POST /api/trackings, it should create and return the tracking')]
    #[Test]
    public function createTrackingController_WhenDataOk_ReturnsTracking(): void
    {
        // ARRANGE
        $tracking = TrackingFactory::new()->withoutPersisting()->create()->object();

        $payload = (new TrackingPayload())
            ->setDate($tracking->getDate())
            ->setExpenseId($tracking->getExpense()->getId())
            ->setIncomeId($tracking->getIncome()->getId());

        $trackingResponse = (new TrackingResponse())
            ->setId($tracking->getId());

        $this->trackingService
            ->expects($this->once())
            ->method('create')
            ->willReturn($trackingResponse);

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
        $this->assertEquals($trackingResponse->getId(), $data['id']);
    }
}
