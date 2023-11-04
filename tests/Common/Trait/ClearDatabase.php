<?php

declare(strict_types=1);

namespace App\Tests\Common\Trait;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Before;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zenstruck\Foundry\Configuration;

trait ClearDatabase
{
    private static ?array $ormTables = null;

    #[Before]
    public function _resetDatabase(): void
    {
        if (! \is_subclass_of(static::class, KernelTestCase::class)) {
            throw new \RuntimeException(\sprintf(
                'The "%s" trait can only be used on TestCases that extend "%s".',
                __TRAIT__,
                KernelTestCase::class
            ));
        }

        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        self::resetORM($container);

        $kernel->shutdown();
    }

    private static function resetORM(ContainerInterface $container): void
    {
        if ($container->has('doctrine')) {
            /** @var EntityManagerInterface $em */
            $em = self::getContainer()->get(EntityManagerInterface::class);

            try {
                // Clear all datas of each tables with raw SQL
                $em->getConnection()
                    ->executeQuery('SET foreign_key_checks = 0;');
                if (self::$ormTables === null) {
                    self::$ormTables = array_keys(
                        $em->getConnection()
                            ->executeQuery('SHOW TABLES')
                            ->fetchAllAssociativeIndexed()
                    );
                }

                foreach (self::$ormTables as $tableName) {
                    $em->getConnection()
                        ->executeQuery("TRUNCATE TABLE `{$tableName}`");
                }
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }
    }

    private static function getConfiguration(ContainerInterface $container): ?Configuration
    {
        if ($container->has('.zenstruck_foundry.configuration')) {
            return $container->get('.zenstruck_foundry.configuration');
        }

        trigger_deprecation(
            'zenstruck\foundry',
            '1.23',
            'Usage of foundry without the bundle is deprecated and will not be possible anymore in 2.0.'
        );

        return null;
    }
}
