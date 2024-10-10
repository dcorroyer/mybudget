<?php

declare(strict_types=1);

namespace App\Tests\Functional\Budget;

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
final class CreateExpenseCategoryControllerTest extends TestBase
{
    private const string API_ENDPOINT = '/api/expense-categories';

    #[TestDox('When you call POST /api/expense-categories, it should create and return the expense categorie')]
    #[Test]
    public function createExpenseCategoryController_WhenDataOk_ReturnsExpenseCategory(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $this->client->loginUser($user);

        $categoryPayload = [
            'name' => 'Habitation',
        ];

        // ACT
        $response = $this->clientRequest(Request::METHOD_POST, self::API_ENDPOINT, $categoryPayload);
        $responseData = $response['data'] ?? [];

        // ASSERT
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');
        self::assertSame('Habitation', $responseData['name']);
    }
}
