<?php

declare(strict_types=1);

namespace Codin\Fault\Inspection;

use Codin\Fault\Exceptions;

class Frame
{
    protected ?string $file;

    protected ?int $line;

    protected ?string $caller;

    protected array $args;

    protected Normaliser $normaliser;

    public static function create(array $frame): self
    {
        $caller = $frame['function'] ?? '(unknown)';
        if (isset($frame['class'], $frame['type'])) {
            $caller = $frame['class'].$frame['type'].$frame['function'];
        }
        return new self($frame['file'] ?? null, $frame['line'] ?? null, $caller, $frame['args'] ?? []);
    }

    final public function __construct(
        ?string $file,
        ?int $line,
        ?string $caller = null,
        array $args = [],
        ?Normaliser $normaliser = null
    ) {
        $this->file = $file;
        $this->line = $line;
        $this->caller = $caller;
        $this->args = $args;
        $this->normaliser = $normaliser ?? new Normaliser();
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function getLine(): ?int
    {
        return $this->line;
    }

    public function getContext(): Context
    {
        if (null === $this->getFile() || null === $this->getLine()) {
            throw Exceptions\ContextError::notAvailable();
        }
        return new Context($this->getFile(), $this->getLine());
    }

    public function getCaller(): ?string
    {
        return $this->caller;
    }

    public function getArguments(): array
    {
        $paramNames = $this->getCallerParams();
        $args = [];

        foreach (\array_values($this->args) as $index => $arg) {
            $name = \array_key_exists($index, $paramNames) ? $paramNames[$index]->getName() : 'param'.($index+1);
            $args[$name] = $this->normaliser->normalise($arg);
        }

        return $args;
    }

    protected function getCallerParams(): array
    {
        $params = [];

        if (null === $this->caller) {
            return $params;
        }

        if (\strpos($this->caller, '->') || \strpos($this->caller, '::')) {
            [$class, $method] = \explode(' ', \str_replace(['->', '::'], ' ', $this->caller));
            if (!class_exists($class)) {
                return $params;
            }
            try {
                $func = (new \ReflectionClass($class))->getMethod($method);
            } catch (\ReflectionException $e) {
                return $params;
            }
        } else {
            try {
                $func = (new \ReflectionFunction($this->caller));
            } catch (\ReflectionException $e) {
                return $params;
            }
        }

        if (!$func->isVariadic()) {
            $params = $func->getParameters();
        }

        return $params;
    }

    public function __toString(): string
    {
        $location = '[internal function]: ';

        if ($this->getFile()) {
            $location = sprintf('%s(%u): ', $this->getFile(), $this->getLine());
        }

        return $location . $this->getCaller();
    }
}
