<?php

declare(strict_types=1);

namespace App\Shared\Exception;

use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(404)]
abstract class AbstractDomainModelNotFoundException extends \RuntimeException
{
    abstract public function model(): string;

    final private function __construct(
        public string|int $id,
        string $message,
    ) {
        parent::__construct(str_replace(['%model%', '%id%'], [$this->model(), $this->id], $message));
    }

    final public static function withId(string|int $id, string $message = '%model% with id "%id%" not found.'): static
    {
        return new static(id: $id, message: $message,);
    }
}
