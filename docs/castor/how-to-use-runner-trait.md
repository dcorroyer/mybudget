# RunnerTrait Class Usage Documentation

The `RunnerTrait` class provides flexible features for command management, with built-in Docker support. Here's how to use the main methods of this class in your applications.

## Basic Configuration

### `getBaseCommand` Method

The `getBaseCommand` method returns the base command, for example, 'composer'. If you want to run a command without specifying a base command, return `null`.

Usage example:
```php
protected function getBaseCommand(): ?string
{
    return 'composer';
}
```

### `allowRunningUsingDocker` Method

The `allowRunningUsingDocker` method determines whether the command should be executed with Docker. Implement this method in your classes that inherit from `RunnerTrait`.

Usage example:
```php
abstract protected function allowRunningUsingDocker(): bool
{
    return true;
}
```

### `allowRunningInsideContainer` Method

The `allowRunningInsideContainer` method configures whether the command should be executed inside a Docker container. If the returned value is `null`, the Docker context configuration will be used.

Usage example:
```php
protected function allowRunningInsideContainer(): ?bool
{
    return true;
}
```

## Docker Configuration

### `withDockerContext` Method

The `withDockerContext` method is used to specify the Docker context to use when running with Docker. 

> [!NOTE]
> By default, it searches for a Docker context corresponding to the base command name in the context passed in the constructor. 
> 
> If no context is found, it looks for a context named 'default'.

Usage example:
```php
protected function withDockerContext(): CastorDockerContext
{
    // Implement logic to retrieve or create the appropriate Docker context.
}
```

## Building Commands

### `add` Method

The `add` method is used to add parts to the command. Use this method to construct the command to be executed.

Usage example:
```php
// Add parts to the command
$this->add('install', '--no-dev');
```

### `addIf` Method

The `addIf` method allows adding parts to the command only if a condition is true. It is useful for constructing conditional commands.

Usage example:
```php
$noDev = true;

// Add '--no-dev' to the command only if $noDev is true
$this->addIf($noDev, '--no-dev');
```

## Executing Commands

### `preRunCommand` Method

The `preRunCommand` method can be implemented to perform specific tasks before executing the command, such as configuring the environment.

Usage example:
```php
protected function preRunCommand(): void
{
    // Implement tasks before running the command
}
```

These examples illustrate how to use the features of the `RunnerTrait` class to build and execute commands with integrated Docker management. Customize these methods based on your specific needs in your classes that inherit from `RunnerTrait`.