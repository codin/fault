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

    public function __construct(?string $file, ?int $line, ?string $caller = null, array $args = [])
    {
        $this->file = $file;
        $this->line = $line;
        $this->caller = $caller;
        $this->args = $args;
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
            $args[$name] = $this->normalise($arg);
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
            } catch (\ReflectionException  $e) {
                return $params;
            }
        } else {
            try {
                $func = (new \ReflectionFunction($this->caller));
            } catch (\ReflectionException  $e) {
                return $params;
            }
        }

        if (!$func->isVariadic()) {
            $params = $func->getParameters();
        }

        return $params;
    }

    /**
     * @param mixed $value
     */
    protected function normalise($value): string
    {
        if ($value === null) {
            return 'null';
        } elseif ($value === false) {
            return 'false';
        } elseif ($value === true) {
            return 'true';
        } elseif (\is_float($value) && (int) $value == $value) {
            return $value.'.0';
        } elseif (\is_integer($value) || \is_float($value)) {
            return (string) $value;
        } elseif (\is_object($value) || \gettype($value) == 'object') {
            return 'Object '.\get_class($value);
        } elseif (\is_resource($value)) {
            return 'Resource '.\get_resource_type($value);
        } elseif (\is_array($value)) {
            return 'Array '.\count($value);
        }

        return $this->truncate($value);
    }

    protected function truncate(string $value, int $threshold = 1024): string
    {
        $size = \mb_strlen($value);

        if ($size > $threshold) {
            return \mb_substr($value, 0, $threshold).' (truncated)';
        }

        return $value;
    }
}
