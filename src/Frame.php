<?php

declare(strict_types=1);

namespace Codin\Fault;

use JsonSerializable;

class Frame implements JsonSerializable
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var int
     */
    protected $line;

    /**
     * @var string
     */
    protected $caller;

    /**
     * @var array
     */
    protected $args = [];

    public static function create(array $params): self
    {
        $frame = new self();

        if (isset($params['file'])) {
            $frame->setFile($params['file']);
        }

        if (isset($params['line'])) {
            $frame->setLine($params['line']);
        }

        if (isset($params['class'])) {
            $frame->setCaller(\sprintf('%s%s%s', $params['class'], $params['type'], $params['function']));
        } elseif (isset($params['function'])) {
            $frame->setCaller($params['function']);
        }

        if (isset($params['args'])) {
            $frame->setArguments($params['args']);
        } else {
            $frame->setArguments([]);
        }

        return $frame;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    public function hasFile(): bool
    {
        return $this->file !== null && \is_readable($this->file);
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function setLine(int $line): void
    {
        $this->line = $line;
    }

    public function hasLine(): bool
    {
        return $this->line !== null;
    }

    public function hasCaller(): bool
    {
        return !empty($this->caller);
    }

    public function getCaller(): string
    {
        return $this->caller;
    }

    public function setCaller(string $caller): void
    {
        $this->caller = $caller;
    }

    public function getArguments(): array
    {
        return $this->args ?: [];
    }

    public function hasArgument(): bool
    {
        return !empty($this->args);
    }

    protected function getParams(): array
    {
        $params = [];
        $caller = $this->getCaller();

        if (empty($caller)) {
            return $params;
        }

        if (\strpos($caller, '->') || \strpos($caller, '::')) {
            [$class, $method] = \explode(' ', \str_replace(['->', '::'], ' ', $caller));
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
                $func = (new \ReflectionFunction($caller));
            } catch (\ReflectionException  $e) {
                return $params;
            }
        }

        if (!$func->isVariadic()) {
            $params = $func->getParameters();
        }

        return $params;
    }

    public function setArguments(array $args): void
    {
        $paramNames = $this->getParams();
        $this->args = [];

        foreach (\array_values($args) as $index => $arg) {
            $name = \array_key_exists($index, $paramNames) ?
                $paramNames[$index]->getName() : 'param'.($index+1);
            $this->args[$name] = $this->normalise($arg);
        }
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function normaliseArray($value): string
    {
        $count = \count($value);

        if ($count === 0) {
            return 'Empty Array';
        }

        if ($count > 100) {
            return 'Array of length ' . $count;
        }

        $types = [];

        foreach ($value as $item) {
            $type = \gettype($item);
            if ('object' === $type) {
                $type = \get_class($item);
            }
            if (!\in_array($type, $types)) {
                $types[] = $type;
            }
        }

        if (\count($types) > 3) {
            return 'Mixed Array of length ' . $count;
        }

        return 'Array<'.\implode('|', $types).'> of length ' . $count;
    }

    /**
     * @param mixed $value
     * @return string
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
            return $this->normaliseArray($value);
        }

        return (new Truncation($value))->truncate();
    }

    public function hasContext(): bool
    {
        return $this->hasFile() && $this->hasLine();
    }

    public function getContext(): Context
    {
        return new Context($this->getFile(), $this->getLine());
    }

    public function toString(): string
    {
        if ($this->hasContext()) {
            return $this->getFile().':'.$this->getLine();
        }
        return $this->getCaller();
    }

    public function toArray(): array
    {
        $frame = [];

        if ($this->hasFile()) {
            $frame['file'] = $this->getFile();
        }

        if ($this->hasLine()) {
            $frame['line'] = $this->getLine();
        }

        if ($this->hasCaller()) {
            $frame['caller'] = $this->getCaller();
        }

        $frame['args'] = $this->getArguments();

        if ($this->hasContext()) {
            $frame['context'] = $this->getContext()->getPlaceInFile();
        }

        return $frame;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
