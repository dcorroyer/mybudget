<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        #[Autowire(env: 'BUILD_TIME')]
        string $buildTime,
        #[Autowire(env: 'VERSION')]
        string $version,
        #[Autowire(env: 'APP_ENV')]
        string $env,
        #[Autowire(param: 'kernel.project_dir')]
        string $projectDir,
        #[Autowire(param: 'kernel.logs_dir')]
        string $logsDir,
        LoggerInterface $logger
    ): Response
    {
        $logger->info('Homepage visited');
        return $this->json([
            'message' => 'Hello world!',
            'build_time' => $buildTime,
            'version' => $version,
            'env' => $env,
            'project_dir' => $projectDir,
            'logs_dir' => $logsDir,
            'logs' => file_exists("$logsDir/$env.log") ? file_get_contents("$logsDir/$env.log") : 'No logs',
        ]);
    }

    #[Route('/throw', name: 'app_throw')]
    public function throw(LoggerInterface $logger): void
    {
        $logger->error('An exception was thrown', ['context' => ['of' => 'the exception']]);
        throw new \Exception('This is an exception');
    }
}
