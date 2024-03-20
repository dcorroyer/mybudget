<?php

declare(strict_types=1);

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\RuleBuilders\Architecture\Architecture;
use Arkitect\Rules\Rule;

return static function (Config $config): void {
    $classSet = ClassSet::fromDir(__DIR__ . '/src');

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
            ->that(new ResideInOneOfTheseNamespaces("{$namespace}\Service"))
            ->should(new HaveNameMatching('*Service'))
            ->because('we want uniform naming for services')
    ];
}