<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Repository\IncomeRepository;
use App\Tests\Common\Factory\IncomeFactory;
use My\RestBundle\Test\Common\Trait\ClearDatabase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

#[Group('integration')]
#[Group('repository')]
#[Group('income')]
#[Group('income-repository')]
class IncomeRepositoryTest extends KernelTestCase
{
    use Factories;
    use ClearDatabase;

    private IncomeRepository $incomeRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::getContainer();
        $this->incomeRepository = $container->get(IncomeRepository::class);
    }

    #[TestDox('When you send a bad income into find method, it should returns null')]
    #[Test]
    public function find_WhenBadData_ReturnsNull()
    {
        // ARRANGE
        $income = '1';

        // ACT
        $income = $this->incomeRepository->find($income);

        // ASSERT
        $this->assertNull($income);
    }

    #[TestDox('When you send an income into find method, it should returns the income')]
    #[Test]
    public function find_WhenDataOk_ReturnsIncome()
    {
        // ARRANGE
        $income = IncomeFactory::new()->create()->object();

        // ACT
        $incomeResponse = $this->incomeRepository->find($income);

        // ASSERT
        $this->assertEquals($income, $incomeResponse);
    }

    #[TestDox('When you send an income into save method with flush, it should returns the income')]
    #[Test]
    public function save_WhenDataOk_ReturnsIncome()
    {
        // ARRANGE
        $income = IncomeFactory::new()->create()->object();

        // ACT
        $incomeResponse = $this->incomeRepository->save($income, true);

        // ASSERT
        $this->assertEquals($income->getId(), $incomeResponse);
    }

    #[TestDox('When you send an income into save method without flush, it should returns null')]
    #[Test]
    public function save_WhenBadData_ReturnsNull()
    {
        // ARRANGE
        $income = IncomeFactory::new()->create()->object();

        // ACT
        $incomeResponse = $this->incomeRepository->save($income);

        // ASSERT
        $this->assertNull($incomeResponse);
    }
}
