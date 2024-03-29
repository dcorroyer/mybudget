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
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
#[Group('integration')]
#[Group('controller')]
#[Group('budget')]
#[Group('budget-controller')]
class DeleteBudgetControllerTest extends TestBase
{
    private const API_ENDPOINT = '/api/budgets';

    #[TestDox('When you call DELETE /api/budgets/{id}, it should delete the budget')]
    #[Test]
    public function deleteBudgetController_WhenDataOk_ReturnsSuccessful(): void
    {
        // ARRANGE
        $user = UserFactory::new()->create()->object();
        $this->client->loginUser($user);

        $budget = BudgetFactory::new([
            'user' => $user,
        ])->create()->object();

        // ACT
        $response = $this->clientRequest(Request::METHOD_DELETE, self::API_ENDPOINT . '/' . $budget->getId());

        // ASSERT
        $this->assertResponseIsSuccessful();
        $this->assertSame(Response::HTTP_NO_CONTENT, $response);
    }
}
