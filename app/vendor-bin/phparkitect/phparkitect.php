<?php

declare(strict_types=1);

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\RuleBuilders\Architecture\Architecture;
use Arkitect\Rules\Rule;

$projectRoot = isset($_SERVER['CI']) ? $_SERVER['GITHUB_WORKSPACE'] : '';
$appPathPrefix = "{$projectRoot}/app";

return static function (Config $config) use ($appPathPrefix): void {
    $classSet = ClassSet::fromDir("{$appPathPrefix}/src");
//    $apiModuleClassSet = ClassSet::fromDir(__DIR__ . '/modules/.bruno/src');
//    $exceptionsModuleClassSet = ClassSet::fromDir(__DIR__ . '/modules/exception/src');

    $layeredArchitectureRules = Architecture::withComponents()
        ->component('AppController')->definedBy('App\Controller\*')
        ->component('AppManager')->definedBy('App\Manager\*')
        ->component('AppTrait')->definedBy('App\Trait\*')
        ->component('AppService')->definedBy('App\Service\*')
        ->component('AppRepository')->definedBy('App\Repository\*')
        ->component('AppEntity')->definedBy('App\Entity\*')
        ->where('AppController')->mayDependOnComponents('AppManager', 'AppEntity')
        ->where('AppService')->mayDependOnComponents('AppRepository', 'AppEntity')
        ->where('AppRepository')->mayDependOnComponents('AppEntity')
        ->where('AppEntity')->mayDependOnComponents('AppRepository')
        ->rules();

    $layeredArchitectureRules = [...$layeredArchitectureRules];

    $config->add($classSet, ...getCommonNamingRulesForNamespace('App'), ...$layeredArchitectureRules);
//    $config->add($apiModuleClassSet, ...getCommonNamingRulesForNamespace('Module\Api'), ...$layeredArchitectureRules);
//    $config->add($exceptionsModuleClassSet, ...getCommonNamingRulesForNamespace('Module\ExceptionHandlerBundle'), ...$layeredArchitectureRules);

//    // Prevent deps between modules and base app
//    $layeredModulesArchitectureRules = Architecture::withComponents()
//        ->component('App')->definedBy('App\*')
//        ->component('Api')->definedBy('Module\Api\*')
//        ->component('Exception')->definedBy('Module\ExceptionHandlerBundle\*')
//        ->where('Api')->shouldNotDependOnAnyComponent()
//        ->where('Exception')->shouldNotDependOnAnyComponent()
//        ->where('App')->shouldNotDependOnAnyComponent()
//        ->rules();

//    $layeredModulesArchitectureRules = [...$layeredModulesArchitectureRules];

//    $config->add($apiModuleClassSet, ...$layeredModulesArchitectureRules);
//    $config->add($exceptionsModuleClassSet, ...$layeredModulesArchitectureRules);

    $domainArchitectureRules = Architecture::withComponents()
        ->component('UserProvider')->definedBy('App\User\*')
        ->component('Todo')->definedBy('App\Todo\*')
        ->where('UserProvider')->mayDependOnComponents('Todo')
        ->where('Todo')->shouldNotDependOnAnyComponent()
        ->rules();

    $config->add($classSet, ...$domainArchitectureRules);
};

function getCommonNamingRulesForNamespace(string $namespace): array
{
    return [
        Rule::allClasses()
            ->that(new ResideInOneOfTheseNamespaces("{$namespace}\Controller"))
            ->should(new HaveNameMatching('*Controller'))
            ->because('we want uniform naming for controllers'),
        Rule::allClasses()
            ->that(new ResideInOneOfTheseNamespaces("{$namespace}\Manager"))
            ->should(new HaveNameMatching('*Manager'))
            ->because('we want uniform naming for managers'),
        Rule::allClasses()
            ->that(new ResideInOneOfTheseNamespaces("{$namespace}\Enum"))
            ->should(new HaveNameMatching('*Enum'))
            ->because('we want uniform naming for enums'),
        Rule::allClasses()
            ->that(new ResideInOneOfTheseNamespaces("{$namespace}\Trait"))
            ->should(new HaveNameMatching('*Trait'))
            ->because('we want uniform naming for traits'),
        Rule::allClasses()
            ->that(new ResideInOneOfTheseNamespaces("{$namespace}\Repository"))
            ->should(new HaveNameMatching('*Repository'))
            ->because('we want uniform naming for repositories')
    ];
}
