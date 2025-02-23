<?php

declare(strict_types=1);

namespace database;

use Castor\Attribute\AsTask;

use function Castor\context;
use function Castor\io;

#[AsTask(name: 'database:reset', description: 'Reset the database', aliases: ['db:reset'])]
function resetDatabase(bool $force = false): void
{
    $databaseUrl = context()->environment['DATABASE_URL'] ?? null;
    if ($databaseUrl === null) {
        io()->error('DATABASE_URL is not set in the environment.');

        return;
    }

    if (! $force && ! str_contains($databaseUrl, 'root:root@myfitness-database:3306')) {
        io()->warning([
            'The DATABASE_URL is not set to the default value for local development.',
            'Please make sure you are running in non-remote environment.',
            'Otherwise, you may lose all the data in the remote database.',
            '',
            "Current DATABASE_URL: {$databaseUrl}",
            'If you are sure you want to reset the database, run the command with the --force option.',
        ]);

        return;
    }

    $output = console([
            'dbal:run-sql', "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'app';"]
    )
        ->run(context()->withQuiet())
        ->getOutput()
    ;

    if (str_contains($output, 'app')) {
        io()->info('Database "app" already exists.');

        if (io()->confirm('Do you want to drop the database and recreate it?') === false) {
            return;
        }
    }

    console(commands: ['doctrine:database:create', '--if-not-exists'])->run();
    console(commands: ['doctrine:schema:drop', '--full-database', '--force'])->run();
    console(commands: ['doctrine:migrations:migrate', '--no-interaction', '--allow-no-migration'])->run();
    console(commands: ['doctrine:fixtures:load', '--no-interaction', '--append'])->run();
}
