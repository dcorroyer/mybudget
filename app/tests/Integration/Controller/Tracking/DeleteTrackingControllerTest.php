<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Tracking;

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

/**
 * @internal
 */
#[Group('integration')]
#[Group('controller')]
#[Group('tracking')]
#[Group('tracking-controller')]
class DeleteTrackingControllerTest extends WebTestCase
{
    use Factories;

    private const API_ENDPOINT = '/api/trackings';

    private KernelBrowser $client;

    private TrackingService $trackingService;

    private TrackingRepository $trackingRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->loginUser(new User());

        $this->trackingService = $this->createMock(TrackingService::class);
        $this->trackingRepository = $this->createMock(TrackingRepository::class);

        $container = self::getContainer();
        $container->set(TrackingService::class, $this->trackingService);
        $container->set(TrackingRepository::class, $this->trackingRepository);
    }

    #[TestDox('When you call DELETE /api/trackings/{id}, it should update and return NoContent')]
    #[Test]
    public function deleteTrackingController_WhenDataOk_ReturnsNoContent(): void
    {
        // ARRANGE
        $tracking = TrackingFactory::new()->withoutPersisting()->create()->object();

        $this->trackingService
            ->expects($this->once())
            ->method('delete')
            ->willReturn($tracking)
        ;

        $this->trackingRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($tracking)
        ;

        // ACT
        $endpoint = self::API_ENDPOINT . '/' . $tracking->getId();
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
