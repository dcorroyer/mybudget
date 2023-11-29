<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Tracking;

use App\Entity\User;
use App\Repository\TrackingRepository;
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
class GetTrackingControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/trackings';

    private KernelBrowser $client;

    private TrackingRepository $trackingRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->trackingRepository = $this->createMock(TrackingRepository::class);

        $container = self::getContainer();
        $container->set(TrackingRepository::class, $this->trackingRepository);
    }

    #[TestDox('When you call GET /api/trackings/{id}, it should return the tracking')]
    #[Test]
    public function getTrackingController_WhenDataOk_ReturnsTracking(): void
    {
        // ARRANGE
        $tracking = TrackingFactory::new()->withoutPersisting()->create()->object();

        $this->trackingRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($tracking);

        $endpoint = self::API_ENDPOINT . '/' . $tracking->getId();

        // ACT
        $this->client->request(method: 'GET', uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $data = $content['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertEquals($tracking->getId(), $data['id']);
        $this->assertEquals($tracking->getDate()->format('Y-m'), $data['date']);
    }
}
