<?php

declare(strict_types=1);

namespace Codin\Fault;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Throwable;

class Stack implements Countable, IteratorAggregate
{
    /**
     * @var array<Trace>
     */
    protected $exceptions;

    public function __construct(Throwable $exception)
    {
        $this->exceptions = [new Trace($exception)];

        if ($exception = $exception->getPrevious()) {
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
