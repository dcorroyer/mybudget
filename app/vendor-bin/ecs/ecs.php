<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\FinalInternalClassFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\AssignNullCoalescingToCoalesceEqualFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocToCommentFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDataProviderStaticFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestClassRequiresCoversFixer;
use Rector\PHPUnit\PHPUnit60\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\MethodChainingNewlineFixer;
use Symplify\CodingStandard\Fixer\Spacing\StandaloneLineConstructorParamFixer;
use Symplify\CodingStandard\Fixer\Spacing\StandaloneLinePromotedPropertyFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$projectRoot = isset($_SERVER['CI']) ? $_SERVER['GITHUB_WORKSPACE'] : '';
$appPathPrefix = "{$projectRoot}/app";

$paths = [
    "{$appPathPrefix}/src",
    "{$appPathPrefix}/public",
    "{$projectRoot}/.castor/src",
    "{$projectRoot}/.castor/castor.php",
];

if (file_exists("{$appPathPrefix}/tests")) {
    $paths[] = "{$appPathPrefix}/tests";
}

return ECSConfig::configure()
    ->withCache('/var/tmp/ecs')
    ->withRootFiles()
    ->withPaths($paths)
    // add a single rule
    ->withRules([
        NoUnusedImportsFixer::class,
        StandaloneLineConstructorParamFixer::class,
        StandaloneLineInMultilineArrayFixer::class,
    ])
    ->withSkip([
        PhpdocToCommentFixer::class,
        PhpUnitTestClassRequiresCoversFixer::class,
        MethodChainingNewlineFixer::class,
        StandaloneLinePromotedPropertyFixer::class,
        FinalInternalClassFixer::class,
        PhpUnitTestCaseStaticMethodCallsFixer::class,
        AssignNullCoalescingToCoalesceEqualFixer::class,
        PhpUnitStrictFixer::class,
        PhpUnitDataProviderStaticFixer::class,
        AddDoesNotPerformAssertionToNonAssertingTestRector::class,
        GeneralPhpdocAnnotationRemoveFixer::class,
    ])
    ->withConfiguredRule(
        LineLengthFixer::class,
        [
            LineLengthFixer::LINE_LENGTH => 120,
        ],
    )
    ->withPreparedSets(
        psr12: true,
        //common: true,
        symplify: true,
        arrays: true,
        comments: true,
        docblocks: true,
        spaces: true,
        namespaces: true,
        controlStructures: true,
        phpunit: true,
        strict: true,
        cleanCode: true,
    )
    ->withSpacing(
        indentation: '    ',
        lineEnding: '\n',
    )
    ->withPhpCsFixerSets(
        doctrineAnnotation: true,
        per: true,
        perCS: true,
        perCS10: true,
        perCS10Risky: true,
        perCS20: true,
        perCS20Risky: true,
        perCSRisky: true,
        perRisky: true,
        php83Migration: true,
        phpunit100MigrationRisky: true,
        psr1: true,
        psr2: true,
        psr12: true,
        psr12Risky: true,
        phpCsFixer: true,
        phpCsFixerRisky: true,
        symfony: true,
        symfonyRisky: true,
    );
