<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer;
use PhpCsFixer\Fixer\ArrayNotation\TrimArraySpacesFixer;
use PhpCsFixer\Fixer\ArrayNotation\WhitespaceAfterCommaInArrayFixer;
use PhpCsFixer\Fixer\Basic\NoTrailingCommaInSinglelineFixer;
use PhpCsFixer\Fixer\Basic\SingleLineEmptyBodyFixer;
use PhpCsFixer\Fixer\Casing\ClassReferenceNameCasingFixer;
use PhpCsFixer\Fixer\Casing\LowercaseStaticReferenceFixer;
use PhpCsFixer\Fixer\Casing\MagicMethodCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeTypeDeclarationCasingFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalClassFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalPublicMethodForAbstractClassFixer;
use PhpCsFixer\Fixer\ClassNotation\NoBlankLinesAfterClassOpeningFixer;
use PhpCsFixer\Fixer\ClassNotation\NoNullPropertyInitializationFixer;
use PhpCsFixer\Fixer\ClassNotation\NoUnneededFinalMethodFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedTypesFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfStaticAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\ClassUsage\DateTimeImmutableFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededControlParenthesesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\ControlStructure\SimplifiedIfReturnFixer;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\AssignNullCoalescingToCoalesceEqualFixer;
use PhpCsFixer\Fixer\Operator\NoUselessConcatOperatorFixer;
use PhpCsFixer\Fixer\Operator\NoUselessNullsafeOperatorFixer;
use PhpCsFixer\Fixer\Operator\ObjectOperatorWithoutWhitespaceFixer;
use PhpCsFixer\Fixer\Operator\TernaryToElvisOperatorFixer;
use PhpCsFixer\Fixer\Operator\TernaryToNullCoalescingFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertInternalTypeFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitExpectationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\ArrayIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\StatementIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\TypeDeclarationSpacesFixer;
use PhpCsFixer\Fixer\Whitespace\TypesSpacesFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Strict\BlankLineAfterStrictTypesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->cacheDirectory('var/cache/.ecs_cache');
    $ecsConfig->parallel();
    $ecsConfig->fileExtensions(['php', 'yaml', 'md']);
    $ecsConfig->paths([__DIR__ . '/apps', __DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/ecs.php']);

    $ecsConfig->sets([SetList::PSR_12, SetList::CLEAN_CODE]);

    $ecsConfig->rules([
        # Imports
        NoUnusedImportsFixer::class,
        FullyQualifiedStrictTypesFixer::class,
        GlobalNamespaceImportFixer::class,
        NoLeadingImportSlashFixer::class,

        # Arrays
        TrimArraySpacesFixer::class,
        ArrayOpenerAndCloserNewlineFixer::class,
        ArrayIndentationFixer::class,
        NoWhitespaceBeforeCommaInArrayFixer::class,
        WhitespaceAfterCommaInArrayFixer::class,
        ArrayListItemNewlineFixer::class,
        StandaloneLineInMultilineArrayFixer::class,
        NoTrailingCommaInSinglelineFixer::class,

        # Blank Lines
        BlankLineAfterStrictTypesFixer::class,
        NoBlankLinesAfterClassOpeningFixer::class,

        # Spacing
        SingleLineEmptyBodyFixer::class,
        CastSpacesFixer::class,
        TypeDeclarationSpacesFixer::class,
        TypesSpacesFixer::class,

        # Casing
        ClassReferenceNameCasingFixer::class,
        LowercaseStaticReferenceFixer::class,
        MagicMethodCasingFixer::class,
        NativeFunctionCasingFixer::class,
        NativeTypeDeclarationCasingFixer::class,

        # Architecture
        FinalClassFixer::class,
        FinalPublicMethodForAbstractClassFixer::class,
        ProtectedToPrivateFixer::class,
        VisibilityRequiredFixer::class,
        DateTimeImmutableFixer::class,
        NoUselessElseFixer::class,

        # Operators
        AssignNullCoalescingToCoalesceEqualFixer::class,
        NoUselessConcatOperatorFixer::class,
        NoUselessNullsafeOperatorFixer::class,
        ObjectOperatorWithoutWhitespaceFixer::class,
        TernaryToElvisOperatorFixer::class,
        TernaryToNullCoalescingFixer::class,

        # Testing
        PhpUnitConstructFixer::class,
        PhpUnitDedicateAssertFixer::class,
        PhpUnitDedicateAssertInternalTypeFixer::class,
        PhpUnitExpectationFixer::class,

        # DocBlocks
        PhpdocLineSpanFixer::class,

        # Others
        LineLengthFixer::class,
        NoNullPropertyInitializationFixer::class,
        NoUnneededFinalMethodFixer::class,
        SelfAccessorFixer::class,
        SelfStaticAccessorFixer::class,
        NoUnneededControlParenthesesFixer::class,
        SimplifiedIfReturnFixer::class,
        TrailingCommaInMultilineFixer::class,
        DeclareStrictTypesFixer::class,
        StrictComparisonFixer::class,
        SingleQuoteFixer::class,
        StatementIndentationFixer::class,
        VoidReturnFixer::class,

    ]);

    $ecsConfig->ruleWithConfiguration(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ]);

    $ecsConfig->ruleWithConfiguration(LineLengthFixer::class, [
        LineLengthFixer::LINE_LENGTH => 120,
    ]);

    $ecsConfig->ruleWithConfiguration(YodaStyleFixer::class, [
        'equal' => true,
        'identical' => true,
        'less_and_greater' => true,
    ]);

    $ecsConfig->ruleWithConfiguration(PhpdocLineSpanFixer::class, [
        'const' => 'single',
        'property' => 'single',
        'method' => 'single',
    ]);

    $ecsConfig->ruleWithConfiguration(PhpUnitMethodCasingFixer::class, [
        'case' => PhpUnitMethodCasingFixer::SNAKE_CASE,
    ]);

    $ecsConfig->ruleWithConfiguration(OrderedTypesFixer::class, [
        'null_adjustment' => 'always_last',
    ]);
};
