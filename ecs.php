<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Phpdoc\AlignMultilineCommentFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ])
    ->withConfiguredRule(AlignMultilineCommentFixer::class, [
        'comment_type' => 'all_multiline',
    ])
    // add a single rule
    ->withRules([
        NoUnusedImportsFixer::class,
    ])
    // add sets - group of rules
    ->withPreparedSets(
        psr12: true,
        common: true,
        symplify: true,
        // arrays: true, # Already included in common
        // comments: true, # Already included in common
        // docblocks: true, # Already included in common
        // spaces: true, # Already included in common
        // namespaces: true, # Already included in common
        // controlStructures: true, # Already included in common
        // phpunit: true, # Already included in common
        strict: true,
        cleanCode: true,
    )
    ->withPhpCsFixerSets(
        doctrineAnnotation: true,
        php82Migration: true,
        phpunit100MigrationRisky: true,
        psr12: true,
        psr12Risky: true,
        symfony: true,
        symfonyRisky: true,
    );
