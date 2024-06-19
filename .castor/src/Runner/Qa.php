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

    public function __construct(?Context $context = null, ?ContainerDefinition $containerDefinition = null, bool $preventRunningUsingDocker = false)
    {
        parent::__construct(
            context: $context,
            containerDefinition: $containerDefinition ?? ContainerDefinitionBag::php(),
            preventRunningUsingDocker: $preventRunningUsingDocker
        );

        $this->addIf($containerDefinition?->name, '--container', $containerDefinition?->name);
    }

    protected function preRunCommand(): void
    {
        $this->install();
    }

    #[AsTaskMethod(aliases: ['qa:update'])]
    public function install(): void
    {
        if (self::$runOnce) {
            return;
        }

        install_tools();

        self::$runOnce = true;
    }

    #[AsTaskMethod]
    public function ecs(#[AsOption(description: 'Fix the issues')] bool $fix = false): Process
    {
        $this->add('ecs', 'check', '--clear-cache', '--ansi', '--config', '/tools/ecs/ecs.php');

        $this->addIf($fix, '--fix');

        return $this->run();
    }

    #[AsTaskMethod]
    public function phpstan(): Process
    {
        $this->add('phpstan', 'clear-result-cache')->run();

        return $this
            ->add('phpstan', 'analyse', '--configuration', '/tools/phpstan/phpstan.neon', '--memory-limit=2G')
            ->run()
        ;
    }

    #[AsTaskMethod]
    public function rector(#[AsOption(description: 'Fix the issues')] bool $fix = false): Process
    {
        $this->add('rector', 'process', '--clear-cache', '--config', '/tools/rector/rector.php');

        $this->addIf(! $fix, '--dry-run');

        return $this->run(qa_context()->withAllowFailure(! $fix));
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
}

function qa(): Qa
{
    return new Qa();
}
