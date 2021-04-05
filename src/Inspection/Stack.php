<?php

declare(strict_types=1);

namespace Codin\Fault\Inspection;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Throwable;

class Stack implements Countable, IteratorAggregate
{
    protected array $exceptions = [];

    public function __construct(Throwable $exception)
    {
        $this->exceptions = [new Trace($exception)];

        while ($exception = $exception->getPrevious()) {
            $this->exceptions[] = new Trace($exception);
        }
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->exceptions);
    }

    public function count(): int
    {
        return count($this->exceptions);
    }
}
