<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Repository\IncomeRepository;
use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\IncomeFactory;
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
#[Group('income')]
#[Group('income-repository')]
final class IncomeRepositoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;
    private IncomeRepository $incomeRepository;

    #[\Override]
    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::getContainer();
        $this->incomeRepository = $container->get(IncomeRepository::class);
    }

    #[TestDox('When you send a bad income into find method, it should returns null')]
    #[Test]
    public function find_WhenBadData_ReturnsNull(): void
    {
        // ARRANGE
        $income = '1';

        // ACT
        $income = $this->incomeRepository->find($income);

        // ASSERT
        self::assertNull($income);
    }

    #[TestDox('When you send an income into find method, it should returns the income')]
    #[Test]
    public function find_WhenDataOk_ReturnsIncome(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $budget = BudgetFactory::createOne([
            'user' => $user,
        ])->_real();
        $income = IncomeFactory::createOne([
            'budget' => $budget,
        ])->_real();

        // ACT
        $incomeResponse = $this->incomeRepository->find($income);

        // ASSERT
        self::assertSame($income, $incomeResponse);
    }

    #[TestDox('When you send an income into save method with flush, it should returns the income')]
    #[Test]
    public function save_WhenDataOk_ReturnsIncome(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $budget = BudgetFactory::createOne([
            'user' => $user,
        ])->_real();
        $income = IncomeFactory::createOne([
            'budget' => $budget,
        ])->_real();

        // ACT
        $incomeResponse = $this->incomeRepository->save($income, true);

        // ASSERT
        self::assertSame($income->getId(), $incomeResponse);
    }

    #[TestDox('When you send an income into save method without flush, it should returns null')]
    #[Test]
    public function save_WhenBadData_ReturnsNull(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $budget = BudgetFactory::createOne([
            'user' => $user,
        ])->_real();
        $income = IncomeFactory::createOne([
            'budget' => $budget,
        ])->_real();

        // ACT
        $incomeResponse = $this->incomeRepository->save($income);

        // ASSERT
        self::assertNull($incomeResponse);
    }
}
