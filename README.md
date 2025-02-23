# PHP Docker Development Environment

This project provides a comprehensive Docker-based development environment for PHP applications, with a focus on Symfony projects. It uses [Castor](https://github.com/jolicode/castor) for task automation and provides a robust development workflow.

## Requirements

- Docker
- PHP 8.1+
- [Castor](https://github.com/jolicode/castor) installed globally

## Quick Start

1. Clone the repository:

```bash
git clone <repository-url>
cd myfitness
```

2. Run the setup wizard:

```bash
castor setup
```

3. Start the environment:

```bash
castor start
```

## Available Commands

Here are some of the most commonly used commands:

- `castor setup` - Initial project setup wizard
- `castor start` - Build and start containers
- `castor stop` - Stop containers
- `castor restart` - Restart containers
- `castor install` - Install project dependencies
- `castor reset-project` - Reset to a fresh Symfony installation
- `castor shell` - Enter PHP container shell
- `castor qa:all` - Run all quality assurance checks

For a complete list of available commands:

```bash
castor list
```

## Project Structure

```
.
├── .castor/            # Castor task definitions and utilities
├── .docker/            # Docker-related files
├── app/               # Your Symfony application
├── compose.yaml       # Docker Compose configuration
└── compose.override.yaml  # Local Docker Compose overrides
```

## Documentation

For more detailed information, please check the following documentation:

- [Setup Guide](docs/setup.md)
- [Development Workflow](docs/development.md)
- [Quality Assurance](docs/quality-assurance.md)
- [Docker Configuration](docs/docker.md)
- [Database Operations](docs/database.md)
- [Deployment](docs/deployment.md)

## License

This project is licensed under the MIT License - see the LICENSE file for details.
