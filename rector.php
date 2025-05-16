<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector;
use Rector\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector;
use Rector\Strict\Rector\Ternary\DisallowedShortTernaryRuleFixerRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap/app.php',
        __DIR__.'/database',
        __DIR__.'/public',
    ])
    ->withSkip([
        PrivatizeFinalClassPropertyRector::class,
        RemoveUnusedPublicMethodParameterRector::class  => [__DIR__ . '/app/Policies'],
        ClosureToArrowFunctionRector::class             => [__DIR__ . '/app/Providers/FortifyServiceProvider.php'],
        BooleanInIfConditionRuleFixerRector::class      => [__DIR__ . '/app/Filament/Clusters/Articles/Resources/ArticleReportResource/Widgets/ArticleReportingChartWidget.php'],
        ReturnBinaryOrToEarlyReturnRector::class        => [__DIR__ . '/app/Providers/TelescopeServiceProvider.php'],
        DisallowedShortTernaryRuleFixerRector::class    => [__DIR__ . '/app/Services/AgentService.php'],
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
        strictBooleans: true,
    )
    ->withPhpSets();
