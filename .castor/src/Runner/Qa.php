<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger\Runner;

use Castor\Attribute\AsOption;
use Castor\Context;
use Symfony\Component\Process\Process;
use TheoD\MusicAutoTagger\ContainerDefinitionBag;
use TheoD\MusicAutoTagger\Docker\ContainerDefinition;
use TheoD02\Castor\Classes\AsTaskClass;
use TheoD02\Castor\Classes\AsTaskMethod;

use function Castor\io;

#[AsTaskClass]
class Qa extends Runner
{
    private static bool $runOnce = false;
    private static bool $runInstall = true;

    public function __construct(
        ?Context $context = null,
        ?ContainerDefinition $containerDefinition = null,
        bool $preventRunningUsingDocker = false,
    ) {
        parent::__construct(
            context: $context,
            containerDefinition: $containerDefinition ?? ContainerDefinitionBag::php(),
            preventRunningUsingDocker: $preventRunningUsingDocker,
        );

        $this->addIf($containerDefinition?->name, '--container', $containerDefinition?->name);
    }

    #[AsTaskMethod(aliases: ['qa:phpunit'])]
    public function phpunit(): Process
    {
        $this->disableInstall();

        return $this
            ->add('vendor/bin/phpunit', '--configuration', '/app/phpunit.xml.dist')
            ->run()
        ;
    }

    public function disableInstall(): void
    {
        self::$runInstall = false;
    }

    #[AsTaskMethod]
    public function preCommit(): void
    {
        io()->title('Running QA tools - Pre-commit hook');

        io()->section('Running ECS');
        $this->ecs(fix: true);

        io()->section('Running Rector');
        $this->rector(fix: true);

        io()->section('Running PHPStan');
        $this->phpstan();

        io()->section('Running PHParkitect');
        $this->phparkitect();

        io()->section('Running PHPMD');
        $this->phpmd();
    }

    #[AsTaskMethod]
    public function ecs(#[AsOption(description: 'Fix the issues')] bool $fix = false): Process
    {
        $this->add('ecs', 'check', '--clear-cache', '--ansi', '--config', '/tools/ecs/ecs.php');

        $this->addIf($fix, '--fix');

        return $this->run();
    }

    #[AsTaskMethod]
    public function rector(#[AsOption(description: 'Fix the issues')] bool $fix = false): Process
    {
        $this->add('rector', 'process', '--clear-cache', '--config', '/tools/rector/rector.php');

        $this->addIf(! $fix, '--dry-run');

        return $this->run(qa_context()->withAllowFailure(! $fix));
    }

    #[AsTaskMethod]
    public function phpstan(bool $pro = false, bool $watch = false): Process
    {
        $this->add('phpstan', 'clear-result-cache')->run();

        $runPhpstan = function () use ($pro): Process {
            return $this
                ->add(
                    'phpstan',
                    'analyse',
                    '--configuration',
                    '/tools/phpstan/phpstan.neon',
                    '--memory-limit=2G',
                    '-vv'
                )
                ->addIf($pro, '--pro')
                ->run($this->context->withAllowFailure())
            ;
        };

        if ($watch) {
            do {
                $process = $runPhpstan();
                $hasFailed = $process->getExitCode() !== 0;
            } while ($hasFailed === true && io()->confirm('Press enter to run phpstan again'));

            io()->newLine();
            io()->success('Oh, you\'ve done it! 🎉 You fixed all the issues! 🎉');

            return $process;
        }

        return $runPhpstan();
    }

    #[AsTaskMethod(aliases: ['qa:arki'])]
    public function phparkitect(): Process
    {
        return $this
            ->add('phparkitect', 'check', '--ansi', '--config', '/tools/phparkitect/phparkitect.php')
            ->run()
        ;
    }

    #[AsTaskMethod(aliases: ['qa:phpmd'])]
    public function phpmd(): Process
    {
        $process = $this
            ->add('phpmd', '/app/src', 'text', 'codesize')
            ->run()
        ;

        io()->success('PHPMD has been executed successfully');

        return $process;
    }

    protected function preRunCommand(): void
    {
        if (self::$runOnce) {
            return;
        }

        if (self::$runInstall) {
            $this->install();
        }

        self::$runOnce = true;
    }

    public function install(): void
    {
        install_tools();
    }
}

function qa(): Qa
{
    return new Qa();
}
