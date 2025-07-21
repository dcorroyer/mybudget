# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

MyBudget is a personal finance management application with:
- **Backend**: Symfony 7.1 (PHP 8.3+) with FrankenPHP, PostgreSQL 16, JWT authentication
- **Frontend**: React 18.3 with TypeScript, Mantine UI, TanStack Query, Vite
- **Architecture**: Clean architecture with domain separation (Budget, Savings, Shared)

## Essential Commands

### Backend Development
```bash
# All backend commands must be run from the app/ directory
cd app

# Install dependencies
composer install

# Run database migrations
./bin/console doctrine:migrations:migrate

# Run tests
./bin/phpunit                              # Full test suite
./bin/phpunit tests/Unit/                  # Unit tests only
./bin/phpunit tests/Functional/            # Functional tests only
./bin/phpunit --filter testMethodName      # Single test method
./bin/phpunit tests/path/to/TestFile.php   # Single test file

# Code quality
./vendor/bin/ecs check                     # Check code style
./vendor/bin/ecs check --fix               # Fix code style
./vendor/bin/phpstan analyse               # Static analysis
```

### Frontend Development
```bash
# All frontend commands must be run from the app/ directory
cd app

# Install dependencies
pnpm install

# Development
pnpm dev        # Start dev server (port 5173)
pnpm build      # Build for production

# Code quality
pnpm lint       # Run ESLint
pnpm lint:fix   # Fix linting issues
pnpm format     # Format with Prettier

# API client generation
pnpm generate-api:download  # Download OpenAPI spec and generate TypeScript client
pnpm generate-api          # Generate client from existing spec
```

### Docker Commands
```bash
# Start development environment
docker compose up -d

# Access PHP container
docker compose exec php bash

# Database is accessible on port 5455
```

## Architecture & Code Organization

### Backend Structure
```
app/src/
├── Budget/          # Budget domain (income, expenses)
├── Savings/         # Savings/accounts domain
└── Shared/          # Common code, utilities
```

### Frontend Structure
```
app/assets/
├── api/            # Generated API client & types
├── components/     # Shared UI components
├── features/       # Feature modules (auth, budgets, savings)
├── hooks/          # Custom React hooks
└── layouts/        # Layout components
```

### Key Patterns
- **API**: RESTful with OpenAPI documentation at `/api/doc`
- **Authentication**: JWT tokens with refresh mechanism
- **State Management**: TanStack Query for server state
- **Forms**: Mantine forms with Zod validation
- **Testing**: PHPUnit for backend, organized by test type

## Development Workflow

1. **API Changes**: Backend changes → run `pnpm generate-api:download` to update frontend types
2. **Database Changes**: Create migration with `./bin/console make:migration`
3. **Testing**: Write tests in appropriate directory (Unit/Integration/Functional)
4. **Code Quality**: Run ECS and PHPStan before committing

## Important Notes

- Frontend runs on `https://mybudget.web.localhost` (requires Traefik)
- API endpoints are prefixed with `/api`
- All user data is scoped by authentication
- Use existing patterns when adding new features
- Check `app/tests/Common/Factory/` for test data builders

## Mentoring Mode

For backend Symfony tasks (particularly in Savings and Budget domains):
- Act as a **mentor**, not a coder
- Guide architecture decisions and best practices
- Suggest design patterns and Symfony conventions
- Help identify potential issues before implementation
- Review approach and provide feedback
- **Do not write the implementation code** - only provide guidance