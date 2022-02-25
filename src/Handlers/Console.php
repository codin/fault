<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Codin\Fault\Contracts\ExceptionHandler;
use Codin\Fault\Exceptions;
use Codin\Fault\Traits;
use Throwable;

class Console implements ExceptionHandler
{
    use Traits\ExceptionMessage, Traits\ExceptionStack;

    /**
     * @var resource
     */
    protected $stream;

    public function __construct(?string $io = null)
    {
        if (null === $io) {
            $io = 'php://stderr';
        }
        $stream = \fopen($io, 'w');
        if (false === $stream) {
            throw Exceptions\StreamError::failedOpening($io);
        }
        $this->stream = $stream;
    }

    public function __destruct()
    {
        \fclose($this->stream);
    }

    protected function write(string $msg): void
    {
        \fwrite($this->stream, $msg);
    }

    protected function writeln(string $msg): void
    {
        $this->write($msg."\n");
    }

    public function handle(Throwable $e): void
    {
        $indent = '    ';
        $doubleIndent = $indent.$indent;

        foreach ($this->getStack($e) as $trace) {
            $text = $this->getMessage($trace->getException());
            $this->writeln($text."\n");
            $this->writeln($indent.$trace->getException()->getFile().':'.$trace->getException()->getLine());
            $this->writeln('');

            foreach ($trace->getContext()->getPlaceInFile() as $num => $line) {
                $lineIndent = $doubleIndent;

                if ($num === $trace->getException()->getLine()) {
                    $lineIndent = $indent.'--> ';
                }

                $text = $lineIndent.$num.' '.\rtrim($line);
                $this->writeln($text);
            }

            $this->writeln('');

            foreach ($trace->getFrames() as $index => $frame) {
                $context = $frame->getFile() ? $frame->getFile().':'.$frame->getLine() : $frame->getCaller();
                $this->writeln($indent.$context);
            }

            $this->writeln('');
        }
    }
}
