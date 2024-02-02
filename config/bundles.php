<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Zenstruck\Foundry\ZenstruckFoundryBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use My\RestBundle\MyRestBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;
use Pentatrion\ViteBundle\PentatrionViteBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;


return [
    FrameworkBundle::class => ['all' => true],
    DoctrineBundle::class => ['all' => true],
    DoctrineMigrationsBundle::class => ['all' => true],
    MakerBundle::class => ['dev' => true],
    ZenstruckFoundryBundle::class => ['dev' => true, 'test' => true],
    DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],
    NelmioApiDocBundle::class => ['dev' => true, 'test' => true],
    KnpPaginatorBundle::class => ['all' => true],
    MyRestBundle::class => ['all' => true],
    SecurityBundle::class => ['all' => true],
    LexikJWTAuthenticationBundle::class => ['all' => true],
    StofDoctrineExtensionsBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true],
    TwigExtraBundle::class => ['all' => true],
    PentatrionViteBundle::class => ['all' => true],
    WebProfilerBundle::class => ['dev' => true, 'test' => true],
    MonologBundle::class => ['all' => true],
    DebugBundle::class => ['dev' => true],
];
