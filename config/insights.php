<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenGlobals;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\NoSilencedErrorsSniff;
use SlevomatCodingStandard\Sniffs\Classes\DisallowLateStaticBindingForConstantsSniff;
use SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\AssignmentInConditionSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff;
use SlevomatCodingStandard\Sniffs\Functions\FunctionLengthSniff;

return [
    'exclude' => [
        'src/Traits/DisableExceptionHandling.php',
    ],
    'remove' => [
        ForbiddenPublicPropertySniff::class,
        ForbiddenGlobals::class,
        DisallowLateStaticBindingForConstantsSniff::class,
        AssignmentInConditionSniff::class,
        DisallowEmptySniff::class,
        NoSilencedErrorsSniff::class,

        SuperfluousInterfaceNamingSniff::class,
        SuperfluousExceptionNamingSniff::class,
        ForbiddenNormalClasses::class,
        ForbiddenTraits::class,
        FunctionLengthSniff::class,

        CyclomaticComplexityIsHigh::class,
    ],

    'config' => [
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 160,
            'absoluteLineLimit' => 160,
        ],
    ],
];
