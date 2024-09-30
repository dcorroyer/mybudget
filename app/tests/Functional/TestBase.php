<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @internal
 */
class TestBase extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    protected KernelBrowser $client;

    #[\Override]
    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = self::createClient();
    }

    public function clientRequest(string $method, string $endpoint, array $payload = []): array|int|null
    {
        $this->client->request(method: $method, uri: $endpoint, server: [
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($payload));

        return json_decode(
            $this->client->getResponse()->getContent(),
            true
        ) ?? $this->client->getResponse()->getStatusCode();
    }
}
