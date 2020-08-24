<?php

declare(strict_types=1);

namespace Codin\Fault;

use Throwable;

class Trace
{
    /**
     * @var Throwable
     */
    protected $exception;

    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function getException(): Throwable
    {
        return $this->exception;
    }

    public function getContext(): Context
    {
        return new Context($this->exception->getFile(), $this->exception->getLine());
    }

    protected function getDebugBacktrace(): array
    {
        $trace = [];
        $capture = false;

        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $frame) {
            if (isset($frame['file'], $frame['line']) && $this->exception->getFile() === $frame['file'] && $this->exception->getLine() === $frame['line']) {
                $capture = true;
            }
            if ($capture) {
                $trace[] = $frame;
            }
        }

        return $trace;
    }

    protected function getBacktrace(): array
    {
        $trace = $this->exception->getTrace();
        if (!count($trace)) {
            return $this->getDebugBacktrace();
        }
        return $trace;
    }

    public function getFrames(): array
    {
        $frames = [];

        foreach ($this->getBacktrace() as $params) {
            $frames[] = Frame::create($params);
        }

        return $frames;
    }
}
