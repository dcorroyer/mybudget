<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => [
        'all' => true,
    ],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => [
        'all' => true,
    ],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\MakerBundle\MakerBundle::class => [
        'dev' => true,
    ],
    Zenstruck\Foundry\ZenstruckFoundryBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Nelmio\ApiDocBundle\NelmioApiDocBundle::class => [
        'dev' => true,
    ],
    Symfony\Bundle\TwigBundle\TwigBundle::class => [
        'all' => true,
    ],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => [
        'all' => true,
    ],
    Knp\Bundle\PaginatorBundle\KnpPaginatorBundle::class => [
        'all' => true,
    ],
];
