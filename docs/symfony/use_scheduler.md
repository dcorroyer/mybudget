---
title: Symfony Scheduler Component Documentation
subtitle: Learn to schedule and run background tasks using Symfony Scheduler Component
description: This documentation provides guidance on using Symfony Scheduler Component to schedule and run background tasks.
status: draft
tags:
    - Symfony
    - Scheduler Component
    - Docker
    - Background Tasks
author: Your Name
---

# Symfony Scheduler Component Documentation in Worker Mode with Docker

---

# Description
This documentation guides you through the usage of the Symfony Scheduler Component to schedule and run background tasks in your Symfony application.

# Context
The Symfony Scheduler Component allows you to plan tasks/handlers to run at specific times or periodically, similar to cron tasks. In this example, we will focus on a task handler that runs every 10 seconds to write to a text file.

# Prerequisites
Before following this documentation, ensure you have a Symfony application set up. Familiarity with Symfony Messenger Component is also recommended. If needed, refer to the [Symfony Messenger Component documentation](https://symfony.com/doc/current/messenger.html).

# Documentation
## Task Handler Example
Wrap your task handler class with the `AsPeriodicTask` attribute to schedule it.

```php
#[AsPeriodicTask(frequency: '10 seconds')]
class WriteReportHandler
{
    public function __invoke(SendDailySalesReports $message)
    {
        file_put_contents('report.txt', 'content');
    }
}
```

## Scheduler Registration
The handler will automatically register a scheduler named `default`. To view scheduled tasks, run:

```bash
php bin/console debug:scheduler
```

Output:
```text
Scheduler
=========

default
-------
------------------ ------------------------------------------------------------------------------------ ---------------------------------
Trigger            Provider                                                                             Next Run
------------------ ------------------------------------------------------------------------------------ ---------------------------------
every 10 seconds   Symfony\Component\Scheduler\Messenger\ServiceCallMessage (@App\WriteReportHandler)   Fri, 23 Feb 2024 00:56:12 +0000
------------------ ------------------------------------------------------------------------------------ ---------------------------------
```

## Running the Scheduler
Run the following command to consume messages from the messenger queue (`scheduler_default`):

```bash
php bin/console messenger:consume scheduler_default
```

To automate this process, define a worker template in your `docker-compose.yaml`:

```yaml
x-worker-template:
  worker_template: &worker_template
    build:
      context: .
      dockerfile: Dockerfile
      target: worker-dev
    volumes:
      - ./app:/app
    profiles:
      - worker
```

Then, set up a worker for your `scheduler_default` service:

```yaml
scheduler_default:
  <<: *worker_template
  command: php /app/bin/console messenger:consume scheduler_default -vv
```

Run all workers or a specific scheduler with the following commands:

=== "Run all workers"
    ```bash
    docker compose --profile worker up -d
    ```

=== "Run a specific scheduler"
    ```bash
    docker compose --profile worker up -d scheduler_default
    ```

View scheduler logs with:

```bash
docker compose logs scheduler_default
```

# Conclusion
With this documentation, you can efficiently schedule and run background tasks in Symfony using the Scheduler Component. Automate the process with Docker and enhance your application's performance.

# References
- [Symfony Scheduler Component Documentation](https://symfony.com/doc/current/scheduler.html)
- [Symfony Messenger Component Documentation](https://symfony.com/doc/current/messenger.html)