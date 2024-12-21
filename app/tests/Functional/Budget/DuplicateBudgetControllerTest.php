<?php

declare(strict_types=1);

namespace App\Tests\Functional\Budget;

use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\UserFactory;
use App\Tests\Functional\TestBase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
#[Group('functional')]
#[Group('controller')]
#[Group('budget')]
#[Group('budget-controller')]
final class DuplicateBudgetControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/budgets/duplicate';

    #[TestDox('When you call POST /api/budgets/duplicate, it should clone and return the new budget')]
    #[Test]
    public function duplicateBudgetController_WhenDataOk_ReturnsBudget(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $this->client->loginUser($user);

        $budget = BudgetFactory::createOne([
            'user' => $user,
        ])->_real();

        $expectedDate = Carbon::parse($budget->getDate())
            ->startOfMonth()
            ->addMonth()
        ;

        // ACT
        $response = $this->clientRequest(Request::METHOD_POST, self::API_ENDPOINT . '/' . $budget->getId());

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');
        self::assertSame($expectedDate->format('Y-m'), $response['date']);
    }
}
