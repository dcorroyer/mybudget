<?php

declare(strict_types=1);

namespace App\Command;

use App\Tests\Common\Helper\MockHelperTrait;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

use function Symfony\Component\String\u;
use function Zenstruck\Foundry\faker;

#[AsCommand(name: 'make:endpoint:test', description: 'Generate a endpoint test', aliases: ['met'])]
class GenerateEndpointTestCommand extends Command
{
    private SymfonyStyle $io;

    private array $classes = [];

    public function __construct(
        private readonly RouterInterface $router
    ) {
        parent::__construct();
    }

    public function generateAssertTests(?\ReflectionParameter $mapRequestPayload, ClassType $class): void
    {
        $parameterAsserts = [];

        if ($mapRequestPayload !== null) {
            $mapRequestPayloadClass = new \ReflectionClass($mapRequestPayload->getType()->getName());

            foreach ($mapRequestPayloadClass->getProperties() as $property) {
                $parameterAsserts[] = $property;
            }
        }

        foreach ($parameterAsserts as $parameter) {
            $parametersToGive = [];

            foreach ($parameterAsserts as $parameterAssert) {
                if ($parameter === $parameterAssert) {
                    continue;
                }

                $parametersToGive[] = [
                    'name' => $parameterAssert->getName(),
                    'value' => $this->generateFakeDataFromType($parameterAssert),
                ];
            }

            $this->generatePayloadTests($class, $parameter->getName(), $parametersToGive);
        }

        if (count($parameterAsserts) > 1) {
            $this->generatePayloadTests($class, 'parameters');
        }
    }

    public function generateUse(PhpNamespace $namespace): void
    {
        // $namespace->addUse($currentClass);
        $namespace->addUse(WebTestCase::class);
        $namespace->addUse(MockHelperTrait::class);
        $namespace->addUse(KernelBrowser::class);
        $namespace->addUse(Test::class);
        $namespace->addUse(TestDox::class);
    }

    public function generateTrait(ClassType $class): void
    {
        $class->addTrait(MockHelperTrait::class);
    }

    /**
     * @return array<string>
     */
    public function getChoices(): array
    {
        $choices = [];
        foreach ($this->classes as $types) {
            foreach ($types as $choice) {
                $choices[] = $choice;
            }
        }

        return $choices;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $this->io->title('Generate Unit Test');

        $routes = $this->getCompatibleRouteCollection();

        $actions = array_map(
            fn (Route $route) => u($route->getDefault('_controller'))
                ->afterLast('\\')
                ->toString(),
            $routes->getIterator()
                ->getArrayCopy()
        );

        $actions = array_flip($actions);

        $chosenRoute = $this->processChoice($actions);

        [$controller, $method] = $this->resolveControllerAndMethod($routes->get($actions[$chosenRoute]));

        $methodReflection = new \ReflectionMethod($controller, $method);

        $additionnalNamespace = u($controller)
            ->replace('App\\Controller\\', '')
            ->beforeLast('\\')
            ->toString();

        $fileTestName = u($controller)
            ->afterLast('\\')
            ->toString() . 'Test';

        $baseActionTestController = 'App\\Tests\\Integration\\Controller';

        if ($additionnalNamespace !== '') {
            $baseActionTestController .= '\\' . $additionnalNamespace;
        }

        $namespace = new PhpNamespace($baseActionTestController);

        $class = $this->generateClass(
            namespace: $namespace,
            fileTestName: $fileTestName,
            uri: $actions[$chosenRoute],
            method: current($routes->get($actions[$chosenRoute])->getMethods()),
            parameters: $methodReflection->getParameters()
        );

        // generate code simply by typecasting to string or using echo:
        $printer = new PsrPrinter();

        $namespace->add($class);

        $file = new PhpFile();
        $file->setStrictTypes();
        $file->addNamespace($namespace);

        echo $printer->printFile($file);

        $dirTestName = u($additionnalNamespace)
            ->replace('\\', '/')
            ->toString();

        $fileSystem = new Filesystem();

        $nameFile = sprintf('%s/tests/Integration/Controller/%s', __DIR__ . '/../..', $dirTestName);

        if ($fileSystem->exists($nameFile) === false) {
            $fileSystem->mkdir($nameFile);
        }

        file_put_contents($nameFile . '/' . $fileTestName . '.php', $printer->printFile($file));

        return Command::SUCCESS;
    }

    private function generatePayloadTests(ClassType $class, string $parameterName, array $parameters = []): void
    {
        $nameParam = u($parameterName)
            ->title()
            ->toString();

        $method = $class->addMethod($class->getName() . 'Without' . $nameParam)
            ->addAttribute(Test::class)
            ->addAttribute(
                TestDox::class,
                ['When call ' . $class->getConstant(
                    'URI'
                )->getValue() . '  without ' . $nameParam . ', it should return error']
            )
            ->setPublic()
            ->setReturnType('void')
            ->setBody('');

        if ($parameters !== []) {
            $method->addBody('//ARRANGE');

            $method->addBody('$payload = [');

            foreach ($parameters as $param) {
                $method->addBody('    \'' . $param['name'] . '\' => ' . $param['value'] . ',');
            }

            $method->addBody('];' . PHP_EOL);
        }

        $method->addBody('//ACT');
        $method->addBody('$this->client->request(');
        $method->addBody('    method: self::METHOD,');
        $method->addBody('    uri: self::URI,');
        $method->addBody('    server: [');
        $method->addBody('        \'CONTENT_TYPE\' => \'application/json\',');
        $method->addBody('    ],');
        if ($parameters !== []) {
            $method->addBody('    content: json_encode($payload)');
        }
        $method->addBody(');' . PHP_EOL);

        $method->addBody('//ASSERT');
        $method->addBody('$this->assertResponseStatusCodeSame(422);');
    }

    private function generateFakeDataFromType(\ReflectionProperty $parameter): mixed
    {
        if ($parameter->getType()->isBuiltin()) {
            if ($parameter->getType()->getName() === 'string') {
                return '\'' . faker()->name() . '\'';
            }
        }

        return null;
    }

    /**
     * @param array<\ReflectionParameter> $parameters
     */
    private function generateClass(
        PhpNamespace $namespace,
        string $fileTestName,
        string $uri,
        string $method,
        array $parameters
    ): ClassType {
        $class = new ClassType($fileTestName);

        $class->setExtends(WebTestCase::class);

        $this->generateUse($namespace);

        $this->generateTrait($class);

        $class->addConstant('URI', '/' . u($uri)->after('/')->toString())
            ->setPrivate();

        $class->addConstant('METHOD', $method)
            ->setPrivate();

        $filterParameter = $this->getParameters($parameters);

        $mapRequestPayload = null;

        foreach ($parameters as $parameter) {
            if ($parameter->getAttributes(MapRequestPayload::class) !== []) {
                $mapRequestPayload = $parameter;
            }
        }

        foreach ($filterParameter as $parameter) {
            $namespace->addUse($parameter->getType()->getName());

            $class->addProperty($parameter->getName())
                ->setType($parameter->getType()->getName())
                ->setPrivate();
        }

        $class->addProperty('client')
            ->setType(KernelBrowser::class)
            ->setPrivate();

        //        $this->generateProperties($class, $serviceName, $className);
        //
        $this->generateSetupMethod(class: $class, parameters: $filterParameter);

        $this->generateAssertTests($mapRequestPayload, $class);

        return $class;
    }

    /**
     * @param array<\ReflectionParameter> $parameters
     */
    private function getParameters(array $parameters): array
    {
        $results = [];

        foreach ($parameters as $parameter) {
            if ($parameter->getAttributes(MapRequestPayload::class) === [] && $parameter->getAttributes(
                MapQueryString::class
            ) === []) {
                $results[] = $parameter;
            }
        }

        return $results;
    }

    /**
     * @param array<\ReflectionParameter> $parameters
     */
    private function generateSetupMethod(ClassType $class, array $parameters): void
    {
        $method = $class->addMethod('setup')
            ->setProtected()
            ->setReturnType('void')
            ->setBody('');

        $method->addBody('$this->client = $this->createClient();' . PHP_EOL);

        foreach ($parameters as $parameter) {
            $method->addBody(
                '$this->' . $parameter->getName() . ' = $this->createMockAndSetToContainer(' . u(
                    $parameter->getType()
                        ->getName()
                )->afterLast('\\')
                    ->toString() . '::class);'
            );
        }
    }

    private function getClassFromFileName(string $type, string $fileName, $isTest = false): string
    {
        $baseNameSpace = 'App';

        if ($isTest) {
            return $baseNameSpace . '\\Tests\\Unit\\' . $type;
        }

        $namespace = $baseNameSpace . '\\' . $type . '\\';

        $this->io->writeln($namespace . $fileName);

        return $namespace . $fileName;
    }

    private function processChoice(array $actions): string
    {
        $question = new Question('Please choice your class to tests');

        $question->setAutocompleterValues($actions);

        return $this->io->askQuestion($question);
    }

    private function getServiceName(): array
    {
        $choices = $this->getChoices();

        $question = new Question('Please choice your class to tests');

        $question->setAutocompleterValues($choices);

        $choice = $this->io->askQuestion($question);

        $typeSelected = null;

        foreach ($this->classes as $toto => $type) {
            foreach ($type as $value) {
                if ($value === $choice) {
                    $typeSelected = $toto;
                    break;
                }
            }
        }

        $this->io->writeln('You have just selected: ' . $choice);

        return [
            'type' => $typeSelected,
            'className' => $choice,
        ];
    }

    private function getCompatibleRouteCollection(): RouteCollection
    {
        $baseRouteCollection = $this->router->getRouteCollection();

        $routeCollection = new RouteCollection();
        foreach ($baseRouteCollection as $route) {
            if ($route->getDefault('_controller') === null) {
                continue;
            }
            if (! u($route->getPath())->startsWith(['/api/doc', '/_', '/ping', '/test', '/{path}'])) {
                $routeCollection->add(implode(':', $route->getMethods()) . $route->getPath(), $route);
            }
        }

        return $routeCollection;
    }

    /**
     * @return array{string, string}
     */
    private function resolveControllerAndMethod(Route $route): array
    {
        if (u($route->getDefault('_controller'))->containsAny('::')) {
            [$controller, $method] = explode('::', $route->getDefault('_controller'));
        } else {
            $controller = $route->getDefault('_controller');
            $method = '__invoke';
        }

        return [$controller, $method];
    }
}
