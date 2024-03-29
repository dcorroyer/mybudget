<?php

declare(strict_types=1);

namespace App\Tests\Functional\Budget;

use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\UserFactory;
use App\Tests\Functional\TestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
#[Group('integration')]
#[Group('controller')]
#[Group('budget')]
#[Group('budget-controller')]
class ListBudgetControllerTest extends TestBase
{
    private const API_ENDPOINT = '/api/budgets';

    #[TestDox('When you call GET /api/budgets, it should returns the budgets list')]
    #[Test]
    public function listBudgetController_WhenDataOk_ReturnsBudgetsList(): void
    {
        // ARRANGE
        $user = UserFactory::new()->create()->object();
        $this->client->loginUser($user);

        $budgets = BudgetFactory::new([
            'user' => $user,
        ])->createMany(20);

        // ACT
        $response = $this->clientRequest(Request::METHOD_GET, self::API_ENDPOINT);
        $responseData = $response['data'] ?? [];

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertSame(\count($budgets), \count($responseData));
    }
}
