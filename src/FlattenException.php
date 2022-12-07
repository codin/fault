<?php

declare(strict_types=1);

namespace Codin\Fault;

use Throwable;

class FlattenException
{
    protected string $className;

    protected string $message;

    protected string $file;

    protected int $line;

    protected int $code;

    protected array $trace;

    protected ?FlattenException $previous = null;

    protected Inspection\Normaliser $normaliser;

    public function __construct(Throwable $exception, ?Inspection\Normaliser $normaliser = null)
    {
        $this->setClassName(\get_class($exception));
        $this->setMessage($exception->getMessage());
        $this->setFile($exception->getFile());
        $this->setLine($exception->getLine());
        $this->setCode($exception->getCode());
        $this->setTrace($exception->getTrace());
        if ($previousException = $exception->getPrevious()) {
            $this->setPrevious($previousException);
        }
        $this->normaliser = $normaliser ?? new Inspection\Normaliser();
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function setLine(int $line): void
    {
        $this->line = $line;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function getTrace(): array
    {
        return $this->trace;
    }

    public function setTrace(array $trace): void
    {
        $flatten = function (array $frame): array {
            $frame['args'] = $this->flattenArgs($frame['args'] ?? []);
            return $frame;
        };

        $this->trace = array_map($flatten, $trace);
    }

    protected function flattenArgs(array $args, int $truncate = 18): array
    {
        return array_map(function ($arg) use ($truncate): string {
            return $this->normaliser->normalise($arg, $truncate);
        }, $args);
    }

    public function getPrevious(): ?FlattenException
    {
        return $this->previous;
    }

    public function setPrevious(Throwable $exception): void
    {
        $this->previous = new self($exception);
    }

    public function getTraceAsString(): string
    {
        $output = '';

        foreach ($this->getTrace() as $index => $frame) {
            $output .= $this->getFrameAsString($index, $frame) . "\n";
        }

        return $output;
    }

    protected function getFrameAsString(int $index, array $frame): string
    {
        return (string) Inspection\Frame::create($frame);
    }

    public function __toString(): string
    {
        $output = sprintf('%s in %s:%u', $this->getMessage(), $this->getFile(), $this->getLine()) . "\n" .
                'Stack trace:' . "\n" . $this->getTraceAsString();

        if ($e = $this->getPrevious()) {
            $output .= (string) $e;
        }

        return $output;
    }
}
