<?php

declare(strict_types=1);

namespace Codin\Fault\Handler;

use Codin\Fault\Traits;
use ErrorException;
use Throwable;

class ConsoleHandler implements HandlerInterface
{
    use Traits\ExceptionMessage, Traits\ExceptionStack;

    /**
     * @var string
     */
    protected $stream;

    public function __construct(string $stream = null)
    {
        $this->stream = $stream ?: 'php://stderr';
    }

    protected function write(string $msg): void
    {
        $resource = \fopen($this->stream, 'wb');
        if (false === $resource) {
            throw new ErrorException('failed to open stream to stderr');
        }
        \fwrite($resource, $msg);
        \fclose($resource);
    }

    protected function writeln(string $msg): void
    {
        $this->write($msg."\n");
    }

    public function handle(Throwable $e): void
    {
        $this->writeln('');
        foreach ($this->getStack($e) as $trace) {
            $this->writeln($this->getMessage($trace->getException()));
            $this->writeln('');
            foreach ($trace->getFrames() as $index => $frame) {
                if (!$frame->hasFile()) {
                    continue;
                }
                $this->writeln('    '.$frame->getFile().':'.$frame->getLine());
                if ($index === 0) {
                    $this->writeln('');
                    $lines = $frame->getContext()->getPlaceInFile();
                    foreach ($lines as $num => $line) {
                        if ($num === $frame->getLine()) {
                            $this->writeln('    --> '.$num.' '.rtrim($line));
                        } else {
                            $this->writeln('        '.$num.' '.rtrim($line));
                        }
                    }
                    $this->writeln('');
                }
                if ($frame->hasArgument()) {
                    foreach ($frame->getArguments() as $key => $value) {
                        $this->writeln('        '.$key.' '.rtrim($value));
                    }
                    $this->writeln('');
                }
            }
        }
        $this->writeln('');
    }
}
