<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Codin\Fault\Contracts\ExceptionHandler;
use Codin\Fault\Exceptions;
use Codin\Fault\Inspection\Trace;
use Codin\Fault\Traits;
use Throwable;

class Console implements ExceptionHandler
{
    use Traits\ExceptionMessage;
    use Traits\ExceptionStack;

    /**
     * @var resource
     */
    protected $stream;

    public function __construct(?string $io = null)
    {
        if (null === $io) {
            $io = 'php://stderr';
        }
        $stream = @fopen($io, 'w');
        if (false === $stream) {
            throw Exceptions\StreamError::failedOpening($io);
        }
        $this->stream = $stream;
    }

    public function __destruct()
    {
        @fclose($this->stream);
    }

    protected function write(string $msg): void
    {
        @fwrite($this->stream, $msg);
    }

    protected function writeln(string $msg): void
    {
        $this->write($msg."\n");
    }

    public function handle(Throwable $e): void
    {
        foreach ($this->getStack($e) as $trace) {
            if (!$trace instanceof Trace) {
                continue;
            }
            $this->handleStack($trace);
        }
    }

    protected function handleStack(Trace $trace): void
    {
        $indent = '    ';
        $doubleIndent = $indent.$indent;
        $exception = $trace->getException();

        $text = $this->getMessage($exception);
        $this->writeln($text."\n");
        $this->writeln($indent.$exception->getFile().':'.$exception->getLine());
        $this->writeln('');

        foreach ($trace->getFrames()[0]->getPlaceInFile() as $num => $line) {
            $lineIndent = $doubleIndent;

            if ($num === $trace->getException()->getLine()) {
                $lineIndent = $indent.'--> ';
            }

            $text = $lineIndent.$num.' '.rtrim($line);
            $this->writeln($text);
        }

        $this->writeln('');

        foreach ($trace->getFrames() as $frame) {
            $context = $frame->getFile() ? $frame->getFile().':'.$frame->getLine() : $frame->getCaller();
            $this->writeln($indent.$context);
        }

        $this->writeln('');
    }
}
