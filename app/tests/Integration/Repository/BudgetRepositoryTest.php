<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Repository\BudgetRepository;
use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\UserFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @internal
 */
#[Group('integration')]
#[Group('repository')]
#[Group('budget')]
#[Group('budget-repository')]
final class BudgetRepositoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    private BudgetRepository $budgetRepository;

    #[\Override]
    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::getContainer();
        $this->budgetRepository = $container->get(BudgetRepository::class);
    }

    #[TestDox('When you send an budget into find method, it should returns the budget')]
    #[Test]
    public function find_WhenDataOk_ReturnsBudget(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_save();
        $budget = BudgetFactory::createOne([
            'user' => $user,
        ])->_real();

        // ACT
        $budgetResponse = $this->budgetRepository->find($budget);

        // ASSERT
        self::assertSame($budget, $budgetResponse);
    }

    #[TestDox('When you send an budget into findLatestByUser method, it should returns the budget')]
    #[Test]
    public function findLatestByUser_WhenDataOk_ReturnsBudget(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $budget = BudgetFactory::createOne([
            'user' => $user,
        ])->_real();

        // ACT
        $budgetResponse = $this->budgetRepository->findLatestByUser($user);

        // ASSERT
        self::assertSame($budget, $budgetResponse);
    }
}
