<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\StaticLambdaFixer;
use PhpCsFixer\Fixer\FunctionNotation\UseArrowFunctionsFixer;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

$configAndPublicFolderApps = array_merge(
    glob(__DIR__ . '/apps/*/config', GLOB_ONLYDIR),
    glob(__DIR__ . '/apps/*/public', GLOB_ONLYDIR),
);

return ECSConfig::configure()
    ->withPaths(array_merge($configAndPublicFolderApps, [__DIR__ . '/src', __DIR__ . '/tests']))
    ->withRootFiles()
    ->withFileExtensions(['php', 'md', 'yaml'])
    ->withCache('var/cache/.ecs_cache')
    ->withParallel()

    ->withSkip([
        PhpdocLineSpanFixer::class,
        NotOperatorWithSuccessorSpaceFixer::class,   # add space after negation !true -> ! true
    ])

    ->withSets([
        SetList::SPACES,
        SetList::SYMPLIFY,
        SetList::ARRAY,
        SetList::DOCBLOCK,
        SetList::NAMESPACES,
        SetList::CONTROL_STRUCTURES,
        SetList::PHPUNIT,
        SetList::STRICT,
        SetList::COMMENTS,
        SetList::COMMON,
        SetList::CLEAN_CODE,
        SetList::PSR_12,
    ])

    ->withRules([
        NoUnusedImportsFixer::class,
        StaticLambdaFixer::class,
        UseArrowFunctionsFixer::class,
        NativeFunctionInvocationFixer::class,
        VoidReturnFixer::class,
    ])

    ->withConfiguredRule(GlobalNamespaceImportFixer::class, [
        'import_constants' => true,
        'import_functions' => true,
        'import_classes' => true,
    ])

    ->withConfiguredRule(PhpdocLineSpanFixer::class, [
        'const' => 'single',
        'property' => 'single',
        'method' => 'single',
    ])

    ->withConfiguredRule(YodaStyleFixer::class, [
        'equal' => true,
        'identical' => true,
        'less_and_greater' => true,
        'always_move_variable' => true,
    ])

    ->withConfiguredRule(PhpUnitMethodCasingFixer::class, [
        'case' => PhpUnitMethodCasingFixer::SNAKE_CASE,
    ])

    ->withConfiguredRule(ClassAttributesSeparationFixer::class, [
        'elements' => [
            'property' => ClassAttributesSeparationFixer::SPACING_NONE,
            'const' => ClassAttributesSeparationFixer::SPACING_NONE,
            'method' => ClassAttributesSeparationFixer::SPACING_ONE,
            'trait_import' => ClassAttributesSeparationFixer::SPACING_NONE,
            'case' => ClassAttributesSeparationFixer::SPACING_NONE,
        ],
    ]);
